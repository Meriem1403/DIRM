<?php

namespace App\Tests\Controller\Admin;

use App\Tests\CustomWebTestCase;

class UserCrudControllerTest extends CustomWebTestCase
{
    public function testListeUtilisateursAccessible()
    {
        $client = static::createClient();
        $this->loginAsAdmin($client);

        $client->request('GET', '/admin/user');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('table');
    }
}
