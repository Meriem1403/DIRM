<?php
// tests/Controller/Admin/CategorieCrudControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class CategorieCrudControllerTest extends CustomWebTestCase
{
    public function testListeCategoriesAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // ✅ Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin/categorie');

        $this->assertResponseIsSuccessful();      // HTTP 200
        $this->assertSelectorExists('table');     // Le tableau EasyAdmin est présent
    }
}
