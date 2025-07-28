<?php

namespace App\Tests;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

abstract class CustomWebTestCase extends WebTestCase
{
    protected function loadFixtures(): void
    {
        $databaseTool = self::getContainer()->get(DatabaseToolCollection::class)->get();
        $databaseTool->loadFixtures([UserFixtures::class]);
    }

    protected function loginAsAdmin($client): User
    {
        $this->loadFixtures();
        $admin = self::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneByEmail('admin.dirm@example.com');

        $client->loginUser($admin);
        return $admin;
    }

    protected function loginAsAgent($client): User
    {
        $this->loadFixtures();
        $agent = self::getContainer()
            ->get(EntityManagerInterface::class)
            ->getRepository(User::class)
            ->findOneByEmail('agent.csn@example.com');

        $client->loginUser($agent);
        return $agent;
    }
}
