<?php
// tests/Controller/Admin/DemandeHabilitationCerbereCrudControllerTest.php

namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class DemandeHabilitationCerbereCrudControllerTest extends CustomWebTestCase
{
    public function testListeDemandesHabilitationAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin/demande-habilitation-cerbere');

        $this->assertResponseIsSuccessful();  // HTTP 200
        $this->assertSelectorExists('table'); // Le tableau EasyAdmin doit être présent
    }
}
