<?php

namespace App\Tests\Form;

use App\Tests\CustomWebTestCase;

class DemandeMobiliteFormTest extends CustomWebTestCase
{
    public function testSoumissionFormulaireMobilite()
    {
        $client = static::createClient();
        $this->loginAsAgent($client);

        $crawler = $client->request('GET', '/demandes/mobilite/new');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        $form = $crawler->selectButton('Envoyer')->form([
            'demande_mobilite[prenom]' => 'Test',
            'demande_mobilite[nom]' => 'Agent',
            'demande_mobilite[statutAgent]' => 'Titulaire',
            'demande_mobilite[corps]' => 'Attaché',
            'demande_mobilite[grade]' => 'Attaché principal',
            'demande_mobilite[statut]' => 'en_attente',
            'demande_mobilite[typeDemande]' => 'arrivee',
            'demande_mobilite[natureMutation]' => 'interne',
            'demande_mobilite[siteGeographique]' => 'Site Test',
            'demande_mobilite[fonction]' => 'Testeur',
        ]);

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }
}

