<?php

namespace App\Command;

use App\Entity\NomRisque;
use App\Repository\GoudurixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-nom-risques',
    description: 'Importe les noms de risques depuis la table goudurix vers la table nom_risque'
)]
class ImportNomRisquesCommand extends Command
{
    public function __construct(
        private readonly GoudurixRepository $goudurixRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Import des noms de risques depuis Goudurix');

        // Récupérer tous les titres uniques de la table goudurix
        $io->section('Récupération des titres uniques...');
        
        $query = $this->entityManager->createQuery(
            'SELECT DISTINCT g.titre FROM App\Entity\Goudurix g WHERE g.titre IS NOT NULL AND g.titre != \'\' ORDER BY g.titre ASC'
        );
        
        $titres = $query->getResult();
        $io->info(sprintf('Trouvé %d titres uniques', count($titres)));

        // Vérifier quels noms de risques existent déjà
        $nomRisqueRepository = $this->entityManager->getRepository(NomRisque::class);
        $existingNoms = [];
        foreach ($nomRisqueRepository->findAll() as $nomRisque) {
            $existingNoms[strtolower(trim($nomRisque->getNom()))] = $nomRisque;
        }

        $io->section('Création des entités NomRisque...');
        $created = 0;
        $skipped = 0;

        foreach ($titres as $row) {
            $titre = trim($row['titre']);
            
            if (empty($titre)) {
                continue;
            }

            // Vérifier si ce nom existe déjà (insensible à la casse)
            $titreLower = strtolower($titre);
            if (isset($existingNoms[$titreLower])) {
                $skipped++;
                continue;
            }

            // Créer un nouveau NomRisque
            $nomRisque = new NomRisque();
            $nomRisque->setNom($titre);
            $nomRisque->setActif(true);
            
            // Optionnel : ajouter une description basée sur le premier risque trouvé
            // On ne met pas de description pour éviter les problèmes d'encodage
            // L'utilisateur pourra la remplir manuellement si nécessaire

            $this->entityManager->persist($nomRisque);
            $existingNoms[$titreLower] = $nomRisque; // Marquer comme existant
            $created++;

            // Afficher la progression tous les 10 éléments
            if ($created % 10 === 0) {
                $io->writeln(sprintf('  Créé %d noms de risques...', $created));
            }
        }

        // Sauvegarder en base
        $io->section('Sauvegarde en base de données...');
        $this->entityManager->flush();

        $io->success([
            sprintf('Import terminé !'),
            sprintf('  - Créés : %d', $created),
            sprintf('  - Ignorés (déjà existants) : %d', $skipped),
            sprintf('  - Total dans la base : %d', count($existingNoms))
        ]);

        return Command::SUCCESS;
    }
}

