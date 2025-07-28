<?php

// tests/Controller/Admin/RoleCrudControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class RoleCrudControllerTest extends CustomWebTestCase
{
    public function testListeRolesAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin/role');

        $this->assertResponseIsSuccessful();  // Doit renvoyer HTTP 200
        $this->assertSelectorExists('table'); // Le tableau EasyAdmin doit être présent
    }
}
