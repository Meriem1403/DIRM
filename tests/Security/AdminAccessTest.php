<?php

namespace App\Tests\Security;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class AdminAccessTest extends WebTestCase
{
    public function testAccesAdminNonConnecteRedirigeVersLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/admin');

        $this->assertResponseRedirects('/login');
    }

    public function testAdminPeutAccederDashboard(): void
    {
        $client = static::createClient();

        // Charger les fixtures
        $databaseTool = self::getContainer()
            ->get(DatabaseToolCollection::class)
            ->get();
        $databaseTool->loadFixtures([UserFixtures::class]);

        /** @var User $admin */
        $admin = self::getContainer()
            ->get('doctrine')
            ->getRepository(User::class)
            ->findOneByEmail('admin.dirm@example.com');

        $client->loginUser($admin);
        $client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
        // $this->assertSelectorTextContains('h1', 'Tableau de bord'); // optionnel
    }
}
