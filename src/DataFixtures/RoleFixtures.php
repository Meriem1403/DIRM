<?php

namespace App\DataFixtures;

use App\Entity\Role;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class RoleFixtures extends Fixture
{
    public const ROLE_ADMIN = 'role_admin';
    public const ROLE_AGENT = 'role_agent';
    public const ROLE_CHEF = 'role_chef';

    public function load(ObjectManager $manager): void
    {
        $roles = [
            ['ROLE_ADMIN', 'Administrateur'],
            ['ROLE_AGENT', 'Agent'],
            ['ROLE_CHEF', 'Chef de service'],
        ];

        foreach ($roles as [$code, $label]) {
            $role = new Role();
            $role->setCode($code);
            $role->setLabel($label);
            $manager->persist($role);

            // pour réutiliser dans d'autres fixtures
            $this->addReference($code, $role);
        }

        $manager->flush();
    }
}
