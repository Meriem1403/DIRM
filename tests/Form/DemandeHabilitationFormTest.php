<?php

namespace App\Tests\Form;

use App\DataFixtures\UserFixtures;
use App\DataFixtures\ApplicationCerbereFixtures;
use App\DataFixtures\ProfilCerbereFixtures;
use App\Tests\CustomWebTestCase;
use App\Entity\User;
use App\Entity\ApplicationCerbere;
use App\Entity\ProfilCerbere;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;

class DemandeHabilitationFormTest extends CustomWebTestCase
{
    public function testSoumissionFormulaireHabilitation()
    {
        $client = static::createClient();

        $databaseTool = self::getContainer()
            ->get(DatabaseToolCollection::class)
            ->get();

        $references = $databaseTool->loadFixtures([
            UserFixtures::class,
            ApplicationCerbereFixtures::class,
            ProfilCerbereFixtures::class,
        ]);

        $admin = self::getContainer()->get('doctrine')->getRepository(User::class)
            ->findOneByEmail('admin.dirm@example.com');
        $agent = self::getContainer()->get('doctrine')->getRepository(User::class)
            ->findOneByEmail('agent.csn@example.com');

        $application = self::getContainer()->get('doctrine')->getRepository(ApplicationCerbere::class)->findOneBy([]);
        $profil = self::getContainer()->get('doctrine')->getRepository(ProfilCerbere::class)->findOneBy([
            'application' => $application,
        ]);

        self::assertNotNull($admin, 'Admin introuvable');
        self::assertNotNull($agent, 'Agent introuvable');
        self::assertNotNull($application, 'Application introuvable');
        self::assertNotNull($profil, 'Profil introuvable');

        $client->loginUser($admin);
        $crawler = $client->request('GET', '/demandes/habilitation/new');
        $this->assertResponseIsSuccessful();

        $form = $crawler->selectButton('Envoyer')->form([
            'demande_habilitation_cerbere[reglePortee]' => 'portée test',
            'demande_habilitation_cerbere[restrictions]' => 'aucune',
            'demande_habilitation_cerbere[agent]' => $agent->getId(),
            'demande_habilitation_cerbere[demandeur]' => $admin->getId(),
            'demande_habilitation_cerbere[applications]' => [$application->getId()],
            'demande_habilitation_cerbere[profils]' => [$profil->getId()],
        ]);

        $client->submit($form);

        $this->assertResponseRedirects();
        $client->followRedirect();

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('h1');
    }
}
