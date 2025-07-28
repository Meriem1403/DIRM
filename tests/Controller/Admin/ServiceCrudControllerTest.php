<?php
// tests/Controller/Admin/ServiceCrudControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class ServiceCrudControllerTest extends CustomWebTestCase
{
    public function testListeServicesAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin/service');

        $this->assertResponseIsSuccessful();  // HTTP 200 attendu
        $this->assertSelectorExists('table'); // Le tableau EasyAdmin doit être présent
    }
}
