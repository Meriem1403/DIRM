<?php
// tests/Controller/Admin/DashboardControllerTest.php
namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class DashboardControllerTest extends CustomWebTestCase
{
    public function testDashboardAdminAccessible(): void
    {
        $client = static::createClient();
        $this->loginAsAdmin($client); // ✅ Connexion en tant qu’admin

        $crawler = $client->request('GET', '/admin');

        $this->assertResponseIsSuccessful();    // HTTP 200
        $this->assertSelectorExists('h1');      // Le titre principal doit être présent
        // Si vous souhaitez vérifier le contenu exact :
        // $this->assertSelectorTextContains('h1', 'Tableau de bord');
    }
}
