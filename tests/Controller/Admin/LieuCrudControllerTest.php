<?php

// tests/Controller/Admin/LieuCrudControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class LieuCrudControllerTest extends CustomWebTestCase
{
    public function testListeLieuxAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin/lieu');

        $this->assertResponseIsSuccessful();  // HTTP 200
        $this->assertSelectorExists('table'); // Le tableau EasyAdmin doit être présent
    }
}
