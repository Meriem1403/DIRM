<?php

namespace App\Controller;

use App\Repository\GoudurixRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\LieuRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class GoudurixFrontController extends AbstractController
{
    public function __construct(
        private readonly GoudurixRepository $goudurixRepository,
        private readonly ServiceRepository $serviceRepository,
        private readonly UserRepository $userRepository,
        private readonly LieuRepository $lieuRepository,
        private readonly AdminUrlGenerator $adminUrlGenerator
    ) {}

    #[Route('/goudurix', name: 'goudurix_front')]
    #[IsGranted('ROLE_ADMIN')]
    public function index(Request $request): Response
    {
        // Filtres (mêmes clés que la vue admin)
        $filters = $request->query->all()['filters'] ?? [];

        $criteria = [];
        if (!empty($filters['niveauRisque'])) {
            $criteria['niveauRisque'] = $filters['niveauRisque'];
        }
        if (!empty($filters['statut'])) {
            $criteria['statut'] = $filters['statut'];
        }
        if (!empty($filters['service'])) {
            $serviceId = is_array($filters['service']) ? $filters['service'][0] : $filters['service'];
            $service = $this->serviceRepository->find($serviceId);
            if ($service) { $criteria['service'] = $service; }
        }
        if (!empty($filters['responsable'])) {
            $responsableId = is_array($filters['responsable']) ? $filters['responsable'][0] : $filters['responsable'];
            $responsable = $this->userRepository->find($responsableId);
            if ($responsable) { $criteria['responsable'] = $responsable; }
        }
        if (!empty($filters['lieu'])) {
            $lieuId = is_array($filters['lieu']) ? $filters['lieu'][0] : $filters['lieu'];
            $lieu = $this->lieuRepository->find($lieuId);
            if ($lieu) { $criteria['lieu'] = $lieu; }
        }

        $goudurixEntities = $this->goudurixRepository->findBy($criteria, ['createdAt' => 'DESC']);

        // Mapper vers objets compatibles avec le template (entity.instance + URLs admin)
        $entities = [];
        foreach ($goudurixEntities as $entityInstance) {
            $entityDto = new \stdClass();
            $entityDto->instance = $entityInstance;
            $entityDto->detailUrl = $this->adminUrlGenerator
                ->setController(\App\Controller\Admin\GoudurixCrudController::class)
                ->setAction('detail')
                ->setEntityId($entityInstance->getId())
                ->generateUrl();
            $entityDto->editUrl = $this->adminUrlGenerator
                ->setController(\App\Controller\Admin\GoudurixCrudController::class)
                ->setAction('edit')
                ->setEntityId($entityInstance->getId())
                ->generateUrl();
            $entityDto->deleteUrl = $this->adminUrlGenerator
                ->setController(\App\Controller\Admin\GoudurixCrudController::class)
                ->setAction('delete')
                ->setEntityId($entityInstance->getId())
                ->generateUrl();
            $entities[] = $entityDto;
        }

        $services = $this->serviceRepository->findBy(['actif' => true], ['nom' => 'ASC']);
        $responsables = $this->userRepository->findAll();
        $lieux = $this->lieuRepository->findAll();

        return $this->render('admin/goudurix_cards.html.twig', [
            'entities' => $entities,
            'filters' => $filters,
            'services' => $services,
            'responsables' => $responsables,
            'lieux' => $lieux,
            'formAction' => '/goudurix',
            'backUrl' => '/mes-risques',
        ]);
    }

    #[Route('/goudurix/export-excel', name: 'goudurix_export_excel')]
    #[IsGranted('ROLE_ADMIN')]
    public function exportExcel(Request $request): Response
    {
        // Récupérer les mêmes filtres que dans index()
        $filters = $request->query->all()['filters'] ?? [];

        $criteria = [];
        if (!empty($filters['niveauRisque'])) {
            $criteria['niveauRisque'] = $filters['niveauRisque'];
        }
        if (!empty($filters['statut'])) {
            $criteria['statut'] = $filters['statut'];
        }
        if (!empty($filters['service'])) {
            $serviceId = is_array($filters['service']) ? $filters['service'][0] : $filters['service'];
            $service = $this->serviceRepository->find($serviceId);
            if ($service) { $criteria['service'] = $service; }
        }
        if (!empty($filters['responsable'])) {
            $responsableId = is_array($filters['responsable']) ? $filters['responsable'][0] : $filters['responsable'];
            $responsable = $this->userRepository->find($responsableId);
            if ($responsable) { $criteria['responsable'] = $responsable; }
        }
        if (!empty($filters['lieu'])) {
            $lieuId = is_array($filters['lieu']) ? $filters['lieu'][0] : $filters['lieu'];
            $lieu = $this->lieuRepository->find($lieuId);
            if ($lieu) { $criteria['lieu'] = $lieu; }
        }

        $goudurixEntities = $this->goudurixRepository->findBy($criteria, ['createdAt' => 'DESC']);

        // Créer le fichier Excel
        try {
            // Forcer le chargement via l'autoloader de Composer
            // Utiliser le chemin absolu /vendor/ qui existe dans le conteneur Docker
            $autoloadPath = '/vendor/autoload.php';
            if (!class_exists('PhpOffice\\PhpSpreadsheet\\Spreadsheet', false)) {
                if (file_exists($autoloadPath)) {
                    require_once $autoloadPath;
                }
            }
            
            // Forcer le chargement de toutes les classes PhpSpreadsheet nécessaires
            spl_autoload_call('PhpOffice\\PhpSpreadsheet\\Spreadsheet');
            spl_autoload_call('PhpOffice\\PhpSpreadsheet\\Writer\\Xlsx');
            spl_autoload_call('PhpOffice\\PhpSpreadsheet\\Style\\Fill');
            spl_autoload_call('PhpOffice\\PhpSpreadsheet\\Style\\Alignment');
            spl_autoload_call('PhpOffice\\PhpSpreadsheet\\Style\\Border');
            
            $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Tableau des Risques');

            // En-têtes
            $headers = [
                'ID',
                'Titre',
                'Description',
                'Niveau de Risque',
                'Statut',
                'Service',
                'Responsable',
                'Date de Détection',
                'Date de Résolution',
                'Catégorie',
                'Source',
                'Probabilité',
                'Gravité',
                'Score Risque',
                'Mesures Préventives',
                'Mesures Correctives',
                'Mesure en Cours',
                'Mesure Mise en Place',
                'Commentaires',
                'Lieu',
                'Date de Création',
            ];

            // Style pour les en-têtes
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ];

            // Écrire les en-têtes
            $column = 'A';
            foreach ($headers as $header) {
                $sheet->setCellValue($column . '1', $header);
                $sheet->getStyle($column . '1')->applyFromArray($headerStyle);
                $sheet->getColumnDimension($column)->setAutoSize(true);
                $column++;
            }

            // Données
            $row = 2;
            foreach ($goudurixEntities as $risque) {
                $sheet->setCellValue('A' . $row, $risque->getId());
                $sheet->setCellValue('B' . $row, $risque->getTitre());
                $sheet->setCellValue('C' . $row, $risque->getDescription());
                $sheet->setCellValue('D' . $row, ucfirst($risque->getNiveauRisque() ?? ''));
                $sheet->setCellValue('E' . $row, ucfirst(str_replace('_', ' ', $risque->getStatut() ?? '')));
                $sheet->setCellValue('F' . $row, $risque->getService()?->getNom() ?? '');
                $sheet->setCellValue('G' . $row, $risque->getResponsable() ? $risque->getResponsable()->getPrenom() . ' ' . $risque->getResponsable()->getNom() : '');
                $sheet->setCellValue('H' . $row, $risque->getDateDetection() ? $risque->getDateDetection()->format('d/m/Y') : '');
                $sheet->setCellValue('I' . $row, $risque->getDateResolution() ? $risque->getDateResolution()->format('d/m/Y') : '');
                $sheet->setCellValue('J' . $row, $risque->getCategorie() ?? '');
                $sheet->setCellValue('K' . $row, $risque->getSource() ?? '');
                $sheet->setCellValue('L' . $row, $risque->getProbabilite() ?? '');
                $sheet->setCellValue('M' . $row, $risque->getGravite() ?? '');
                $sheet->setCellValue('N' . $row, $risque->getScoreRisque() ?? '');
                $sheet->setCellValue('O' . $row, $risque->getMesuresPreventives() ?? '');
                $sheet->setCellValue('P' . $row, $risque->getMesuresCorrectives() ?? '');
                $sheet->setCellValue('Q' . $row, $risque->getMesureEnCours() ?? '');
                $sheet->setCellValue('R' . $row, $risque->isMesureMiseEnPlace() ? 'Oui' : 'Non');
                $sheet->setCellValue('S' . $row, $risque->getCommentaires() ?? '');
                $sheet->setCellValue('T' . $row, $risque->getLieu()?->getNom() ?? '');
                $sheet->setCellValue('U' . $row, $risque->getCreatedAt() ? $risque->getCreatedAt()->format('d/m/Y H:i') : '');

                // Style pour les lignes de données (alternance de couleurs)
                $rowStyle = [
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => ($row % 2 == 0) ? 'F2F2F2' : 'FFFFFF'],
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                            'color' => ['rgb' => 'CCCCCC'],
                        ],
                    ],
                    'alignment' => [
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_TOP,
                        'wrapText' => true,
                    ],
                ];
                $sheet->getStyle('A' . $row . ':U' . $row)->applyFromArray($rowStyle);
                
                // Ajuster la hauteur des lignes pour les cellules avec du texte long
                $sheet->getRowDimension($row)->setRowHeight(-1);
                
                $row++;
            }

            // Ajuster la largeur des colonnes avec du texte long
            $sheet->getColumnDimension('C')->setWidth(40); // Description
            $sheet->getColumnDimension('O')->setWidth(30); // Mesures Préventives
            $sheet->getColumnDimension('P')->setWidth(30); // Mesures Correctives
            $sheet->getColumnDimension('Q')->setWidth(30); // Mesure en Cours
            $sheet->getColumnDimension('S')->setWidth(30); // Commentaires

            // Générer le fichier
            $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);

            // Créer un nom de fichier avec la date
            $filename = 'tableau_risques_' . date('Y-m-d_His') . '.xlsx';

            // Créer une réponse avec le fichier Excel
            $response = new Response();
            $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $response->headers->set('Content-Disposition', $response->headers->makeDisposition(
                ResponseHeaderBag::DISPOSITION_ATTACHMENT,
                $filename
            ));

            // Écrire le fichier dans la réponse
            ob_start();
            $writer->save('php://output');
            $response->setContent(ob_get_clean());

            return $response;
        } catch (\Exception $e) {
            throw new \RuntimeException('Erreur lors de la génération du fichier Excel: ' . $e->getMessage(), 0, $e);
        }
    }
}


