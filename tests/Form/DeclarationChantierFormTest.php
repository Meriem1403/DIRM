<?php

namespace App\Tests\Form;

use App\Tests\CustomWebTestCase;

class DeclarationChantierFormTest extends CustomWebTestCase
{
    public function testSoumissionFormulaireDeclarationChantier(): void
    {
        // 1) On crée le client et on se connecte
        $client = static::createClient();
        $this->loginAsAgent($client);

        // 2) On récupère la page du formulaire
        $crawler = $client->request('GET', '/demandes/chantier/new');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('form');

        // 3) On soumet le formulaire sans upload de fichiers
        $client->submitForm('Envoyer', [
            'declaration_chantier[nom]'                     => 'Test',
            'declaration_chantier[prenom]'                  => 'Exploitant',
            'declaration_chantier[email]'                   => 'exploitant@test.fr',
            'declaration_chantier[telephone]'               => '0601020304',
            'declaration_chantier[adresse]'                 => '1 rue du port',
            'declaration_chantier[typeDemande]'             => 'mise_en_chantier',
            'declaration_chantier[activites]'               => ['peche'],
            'declaration_chantier[nomNavire]'               => 'NavireTest',
            'declaration_chantier[quartierImmatriculation]' => 'MA9999',
            'declaration_chantier[jauge]'                   => 10,
            'declaration_chantier[longueur]'                => 12,
            'declaration_chantier[largeur]'                 => 4,
            'declaration_chantier[propulsion]'              => 'thermique',
            'declaration_chantier[puissanceKw]'             => 300,
            'declaration_chantier[vitesse]'                 => '0_12',
            'declaration_chantier[materiau]'                => 'acier',
            'declaration_chantier[portDepart]'              => 'Marseille',
            'declaration_chantier[eloignementCote]'         => '>20',
            'declaration_chantier[dureeSejourMer]'          => 'moins_6h',
            'declaration_chantier[datePoseQuille]'          => '2024-01-01',
            // on ne teste pas les personnesABord ni les fichiers
        ]);

        // 4) On vérifie la redirection puis la page récap
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }
}
