<?php

// tests/Controller/Admin/DomaineServiceCrudControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class DomaineServiceCrudControllerTest extends CustomWebTestCase
{
    public function testListeDomainesServiceAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin/domaine-service');

        $this->assertResponseIsSuccessful();  // HTTP 200
        $this->assertSelectorExists('table'); // Le tableau EasyAdmin doit être présent
    }
}
