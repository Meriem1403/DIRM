<?php

// tests/Controller/Admin/DeclarationChantierCrudControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class DeclarationChantierCrudControllerTest extends CustomWebTestCase
{
    public function testListeDeclarationsAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // ✅ Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin/declaration-chantier');

        $this->assertResponseIsSuccessful();    // HTTP 200
        $this->assertSelectorExists('table');   // Le tableau EasyAdmin doit être présent
    }
}
