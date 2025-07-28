<?php

namespace App\Tests\Security;

use App\DataFixtures\UserFixtures;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class LoginFormTest extends WebTestCase
{
    private \Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool $databaseTool;

    public function testPageLoginAccessible()
    {
        $client = static::createClient();

        // Charger les fixtures
        $this->databaseTool = self::getContainer()
            ->get(DatabaseToolCollection::class)
            ->get();
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $crawler = $client->request('GET', '/login');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');
        $this->assertSelectorExists('input[name="email"]');      // ✅ corrigé ici
        $this->assertSelectorExists('input[name="password"]');   // ✅ corrigé ici
    }

    public function testConnexionAvecIdentifiantsValides()
    {
        $client = static::createClient();

        // Charger les fixtures
        $this->databaseTool = self::getContainer()
            ->get(DatabaseToolCollection::class)
            ->get();
        $this->databaseTool->loadFixtures([UserFixtures::class]);

        $crawler = $client->request('GET', '/login');

        $form = $crawler->selectButton('Connexion')->form([
            'email' => 'admin.dirm@example.com',     // ✅ corrigé ici
            'password' => 'admin123',                // ✅ corrigé ici
        ]);

        $client->submit($form);
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }
}
