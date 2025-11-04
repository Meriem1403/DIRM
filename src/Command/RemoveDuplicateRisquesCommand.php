<?php

namespace App\Command;

use App\Repository\GoudurixRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:remove-duplicate-risques',
    description: 'Supprime les risques en doublon dans la base de données'
)]
class RemoveDuplicateRisquesCommand extends Command
{
    public function __construct(
        private readonly GoudurixRepository $goudurixRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addOption('dry-run', null, InputOption::VALUE_NONE, 'Affiche ce qui serait supprimé sans le faire');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $dryRun = $input->getOption('dry-run');
        
        if ($dryRun) {
            $io->warning('Mode DRY-RUN : aucune suppression ne sera effectuée');
        }

        $io->title('Nettoyage des risques en doublon');

        // Trouver tous les risques et les grouper manuellement
        $allRisques = $this->goudurixRepository->findAll();
        
        // Grouper par titre + service + lieu
        $grouped = [];
        foreach ($allRisques as $risque) {
            $key = sprintf(
                '%s|%s|%s',
                $risque->getTitre(),
                $risque->getService() ? $risque->getService()->getId() : 'null',
                $risque->getLieu() ? $risque->getLieu()->getId() : 'null'
            );
            
            if (!isset($grouped[$key])) {
                $grouped[$key] = [];
            }
            $grouped[$key][] = $risque;
        }
        
        // Filtrer pour garder seulement les groupes avec doublons
        $doublons = array_filter($grouped, function($group) {
            return count($group) > 1;
        });
        
        $io->info(sprintf('Trouvé %d groupes de doublons', count($doublons)));

        $totalSupprimes = 0;
        $totalGardes = 0;

        foreach ($doublons as $key => $risques) {
            // Trier par date de création (garder le plus ancien)
            usort($risques, function($a, $b) {
                return $a->getCreatedAt() <=> $b->getCreatedAt();
            });
            
            // Garder le premier (le plus ancien) et supprimer les autres
            $premier = array_shift($risques);
            $totalGardes++;
            
            $io->writeln(sprintf(
                '  - "%s" (Service: %s, Lieu: %s) : %d doublons, garde ID %d, supprime %d',
                $premier->getTitre(),
                $premier->getService() ? $premier->getService()->getNom() : 'Non spécifié',
                $premier->getLieu() ? $premier->getLieu()->getNom() : 'Non spécifié',
                count($risques) + 1,
                $premier->getId(),
                count($risques)
            ));

            foreach ($risques as $risque) {
                if (!$dryRun) {
                    $this->entityManager->remove($risque);
                }
                $totalSupprimes++;
            }
        }

        if (!$dryRun) {
            $this->entityManager->flush();
            $io->success([
                sprintf('Nettoyage terminé !'),
                sprintf('  - Risques gardés : %d', $totalGardes),
                sprintf('  - Risques supprimés : %d', $totalSupprimes),
            ]);
        } else {
            $io->note([
                sprintf('En mode DRY-RUN :'),
                sprintf('  - Risques qui seraient gardés : %d', $totalGardes),
                sprintf('  - Risques qui seraient supprimés : %d', $totalSupprimes),
            ]);
        }

        return Command::SUCCESS;
    }
}

