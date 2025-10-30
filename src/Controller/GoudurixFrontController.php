<?php

namespace App\Controller;

use App\Repository\GoudurixRepository;
use App\Repository\ServiceRepository;
use App\Repository\UserRepository;
use App\Repository\LieuRepository;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
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
}


