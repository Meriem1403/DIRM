<?php

namespace App\Controller;

use App\Service\MongoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use DateTimeImmutable;

class MongoDemoController extends AbstractController
{
    #[Route('/mongo-demo', name: 'mongo_demo')]
    public function index(MongoService $mongo): Response
    {
        $collection = $mongo->getCollection('test', 'trucs');
        $documents = $collection->find()->toArray();

        return $this->render('mongo/index.html.twig', [
            'documents' => $documents,
        ]);
    }

    #[Route('/mongo-insert', name: 'mongo_insert')]
    public function insert(MongoService $mongo): Response
    {
        $collection = $mongo->getCollection('test', 'trucs');

        $insertResult = $collection->insertOne([
            'titre' => 'Document inséré via Symfony',
            'valeurs' => [9, 8, 7],
            'auteur' => 'Meriem',
            'createdAt' => new DateTimeImmutable()
        ]);

        return new Response('✅ Document inséré avec ID : ' . $insertResult->getInsertedId());
    }
}
