<?php

namespace App\Controller\BD;

use App\Entity\Goudurix;
use App\Entity\Service;
use App\Entity\User;
use App\Entity\Lieu;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\LieuRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class ImportCsvController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly ServiceRepository $serviceRepository,
        private readonly UserRepository $userRepository,
        private readonly LieuRepository $lieuRepository
    ) {}

    #[Route('/admin/import-csv', name: 'import_csv_risques')]
    #[IsGranted('ROLE_ADMIN')]
    public function import(Request $request): Response
    {
        $csvPath = __DIR__ . '/extraction goudurix copie.csv';
        
        if (!file_exists($csvPath)) {
            return new Response('Fichier CSV non trouvé: ' . $csvPath, 404);
        }

        $stats = [
            'total' => 0,
            'imported' => 0,
            'skipped' => 0,
            'errors' => []
        ];

        try {
            $handle = fopen($csvPath, 'r');
            if (!$handle) {
                throw new \RuntimeException('Impossible d\'ouvrir le fichier CSV');
            }

            // Lire l'en-tête
            $headers = fgetcsv($handle, 0, ';');
            if (!$headers) {
                fclose($handle);
                throw new \RuntimeException('Impossible de lire l\'en-tête du CSV');
            }

            // Normaliser les en-têtes (enlever les espaces)
            $headers = array_map('trim', $headers);

            // Lire les données ligne par ligne
            while (($row = fgetcsv($handle, 0, ';')) !== false) {
                $stats['total']++;
                
                if (count($row) < count($headers)) {
                    $stats['skipped']++;
                    $stats['errors'][] = "Ligne {$stats['total']}: Nombre de colonnes insuffisant";
                    continue;
                }

                // Créer un tableau associatif
                $data = array_combine($headers, $row);
                
                try {
                    $risque = $this->createRisqueFromCsv($data);
                    if ($risque) {
                        $this->entityManager->persist($risque);
                        $stats['imported']++;
                    } else {
                        $stats['skipped']++;
                    }
                } catch (\Exception $e) {
                    $stats['skipped']++;
                    $stats['errors'][] = "Ligne {$stats['total']}: " . $e->getMessage();
                }

                // Flush tous les 50 enregistrements pour éviter les problèmes de mémoire
                if ($stats['imported'] % 50 === 0) {
                    $this->entityManager->flush();
                }
            }

            fclose($handle);
            
            // Flush final
            $this->entityManager->flush();

            return $this->render('admin/import_result.html.twig', [
                'stats' => $stats,
            ]);

        } catch (\Exception $e) {
            return new Response('Erreur lors de l\'import: ' . $e->getMessage(), 500);
        }
    }

    private function createRisqueFromCsv(array $data): ?Goudurix
    {
        // Vérifier que les données essentielles sont présentes
        if (empty($data['Risque']) || empty($data['Situation dangereuse'])) {
            return null;
        }

        // Vérifier si un risque similaire existe déjà (pour éviter les doublons)
        $titre = trim($data['Risque']);
        $serviceNom = trim($data['Service'] ?? '');
        $siteNom = trim($data['Site'] ?? '');
        
        // Chercher un risque existant avec le même titre, service et lieu
        $service = $this->findOrCreateService($serviceNom);
        $lieu = null;
        if (!empty($siteNom)) {
            $lieu = $this->findOrCreateLieu($siteNom);
        }
        
        $criteria = [
            'titre' => $titre,
            'service' => $service,
        ];
        if ($lieu) {
            $criteria['lieu'] = $lieu;
        } else {
            $criteria['lieu'] = null;
        }
        
        $existingRisque = $this->entityManager->getRepository(\App\Entity\Goudurix::class)
            ->findOneBy($criteria);
        
        if ($existingRisque) {
            // Risque déjà existant, on retourne null pour ne pas créer de doublon
            return null;
        }

        $risque = new Goudurix();

        // Titre (Risque)
        $risque->setTitre(trim($data['Risque']));

        // Description (Situation dangereuse)
        $risque->setDescription(trim($data['Situation dangereuse']));

        // Statut (mapper les valeurs)
        $statutCsv = trim($data['Statut'] ?? '');
        $statut = $this->mapStatut($statutCsv);
        $risque->setStatut($statut);

        // Niveau de risque (basé sur Priorité finale)
        $priorite = trim($data['Priorité finale'] ?? '');
        $niveauRisque = $this->mapPrioriteToNiveauRisque($priorite);
        $risque->setNiveauRisque($niveauRisque);

        // Service
        $serviceNom = trim($data['Service'] ?? '');
        $service = $this->findOrCreateService($serviceNom);
        $risque->setService($service);

        // Responsable
        $responsableNom = trim($data['Responsable'] ?? '');
        $responsable = $this->findOrCreateUser($responsableNom);
        $risque->setResponsable($responsable);

        // Lieu (Site)
        $lieuNom = trim($data['Site'] ?? '');
        if (!empty($lieuNom)) {
            $lieu = $this->findOrCreateLieu($lieuNom);
            $risque->setLieu($lieu);
        }

        // Mesures
        if (!empty($data['Mesure'])) {
            $mesure = trim($data['Mesure']);
            $etat = trim($data['État'] ?? '');
            
            if ($etat === 'Existante') {
                $risque->setMesuresPreventives($mesure);
                $risque->setMesureMiseEnPlace(true);
            } else {
                $risque->setMesureEnCours($mesure);
                $risque->setMesureMiseEnPlace(false);
            }
        }

        // Retour d'action
        if (!empty($data['Retour d\'action'])) {
            $risque->setRetourAction(trim($data['Retour d\'action']));
        }

        // Commentaires (Mention Spéciale)
        if (!empty($data['Mention Spéciale'])) {
            $risque->setCommentaires(trim($data['Mention Spéciale']));
        }

        // Colonnes additionnelles du CSV
        $risque->setIdaction(trim($data['idaction'] ?? ''));
        $risque->setIdSituD(trim($data['ID_SituD'] ?? ''));
        $risque->setIdDommage(trim($data['ID_Dommage'] ?? ''));
        $risque->setIdMesure(trim($data['ID_Mesure'] ?? ''));
        $risque->setUnite(trim($data['Unité'] ?? ''));
        $risque->setNumero(trim($data['Numéro'] ?? ''));
        $risque->setDommage(trim($data['Dommage'] ?? ''));
        $risque->setEtat(trim($data['État'] ?? ''));
        $risque->setPeriodicite(trim($data['Périodicité'] ?? ''));
        $risque->setNPdf(trim($data['N_pdf'] ?? ''));

        // Prochain contrôle (date)
        if (!empty($data['Prochain contrôle'])) {
            try {
                $prochainControle = \DateTime::createFromFormat('d/m/Y', trim($data['Prochain contrôle']));
                if ($prochainControle) {
                    $risque->setProchainControle($prochainControle);
                }
            } catch (\Exception $e) {
                // Ignorer si la date n'est pas valide
            }
        }

        // Date de détection (par défaut aujourd'hui si pas de date)
        $dateDetection = new \DateTime();
        $risque->setDateDetection($dateDetection);

        // Date de création
        $risque->setCreatedAt(new \DateTime());

        return $risque;
    }

    private function mapStatut(string $statutCsv): string
    {
        $statutCsv = strtolower(trim($statutCsv));
        
        $mapping = [
            'a planifier' => 'en_cours',
            'en cours' => 'en_cours',
            'a contrôler' => 'surveillé',
            'traité' => 'traité',
            'traite' => 'traité',
            'archivé' => 'archivé',
            'archive' => 'archivé',
        ];

        return $mapping[$statutCsv] ?? 'en_cours';
    }

    private function mapPrioriteToNiveauRisque(string $priorite): string
    {
        $priorite = strtoupper(trim($priorite));
        
        // P1 = critique, P2 = élevé, P3 = moyen, P4 = faible
        $mapping = [
            'P1' => 'critique',
            'P2' => 'élevé',
            'P3' => 'moyen',
            'P4' => 'faible',
        ];

        return $mapping[$priorite] ?? 'moyen';
    }

    private function findOrCreateService(string $nom): Service
    {
        if (empty($nom)) {
            // Service par défaut
            $nom = 'Service non spécifié';
        }

        $service = $this->serviceRepository->findOneBy(['nom' => $nom]);
        
        if (!$service) {
            $service = new Service();
            $service->setNom($nom);
            $service->setActif(true);
            $this->entityManager->persist($service);
            $this->entityManager->flush();
        }

        return $service;
    }

    private function findOrCreateUser(string $nom): User
    {
        if (empty($nom)) {
            // Utilisateur par défaut (admin)
            $user = $this->userRepository->findOneBy(['email' => 'admin.dirm@example.com']);
            if ($user) {
                return $user;
            }
        }

        // Essayer de trouver par nom
        $parts = explode(' ', $nom, 2);
        if (count($parts) === 2) {
            $prenom = $parts[0];
            $nomFamily = $parts[1];
            $user = $this->userRepository->createQueryBuilder('u')
                ->where('u.prenom LIKE :prenom AND u.nom LIKE :nom')
                ->setParameter('prenom', $prenom . '%')
                ->setParameter('nom', $nomFamily . '%')
                ->setMaxResults(1)
                ->getQuery()
                ->getOneOrNullResult();
            
            if ($user) {
                return $user;
            }
        }

        // Si pas trouvé, utiliser le premier admin ou créer un utilisateur par défaut
        $user = $this->userRepository->findOneBy(['email' => 'admin.dirm@example.com']);
        if (!$user) {
            // Prendre le premier utilisateur disponible
            $user = $this->userRepository->findOneBy([]);
        }

        if (!$user) {
            throw new \RuntimeException('Aucun utilisateur trouvé pour créer le risque');
        }

        return $user;
    }

    private function findOrCreateLieu(string $nom): ?Lieu
    {
        if (empty($nom)) {
            return null;
        }

        $lieu = $this->lieuRepository->findOneBy(['nom' => $nom]);
        
        if (!$lieu) {
            $lieu = new Lieu();
            $lieu->setNom($nom);
            $this->entityManager->persist($lieu);
            $this->entityManager->flush();
        }

        return $lieu;
    }
}

