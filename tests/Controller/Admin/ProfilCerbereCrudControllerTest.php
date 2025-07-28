<?php

// tests/Controller/Admin/ProfilCerbereCrudControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class ProfilCerbereCrudControllerTest extends CustomWebTestCase
{
    public function testListeProfilsCerbereAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin/profil-cerbere');

        $this->assertResponseIsSuccessful();  // HTTP 200 attendu
        $this->assertSelectorExists('table'); // Le tableau EasyAdmin doit être présent
    }
}
