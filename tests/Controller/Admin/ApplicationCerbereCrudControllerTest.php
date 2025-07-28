<?php

// tests/Controller/Admin/ApplicationCerbereCrudControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class ApplicationCerbereCrudControllerTest extends CustomWebTestCase
{
    public function testListeApplicationsAccessible(): void
    {
        $client = static::createClient();

        // 🔑 On se logue en tant qu’admin pour accéder au backoffice
        $this->loginAsAdmin($client);

        $crawler = $client->request('GET', '/admin/application-cerbere');

        // On attend maintenant bien un 200
        $this->assertResponseIsSuccessful();

        // EasyAdmin affiche un tableau des entités
        $this->assertSelectorExists('table');
    }
}
