<?php

namespace App\Tests\Controller;

use App\Tests\CustomWebTestCase;

class HomeControllerTest extends CustomWebTestCase
{
    public function testPageAccueilAccessible()
    {
        $client = static::createClient();

        $this->loginAsAgent($client);

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }
}
