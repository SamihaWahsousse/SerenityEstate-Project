<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;


class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct()
    {
    }

    public function load(ObjectManager $entityManager): void
    {

        // Create user Manager 
        $manager = new User();
        $manager->setEmail('manager@serenity-estate.com');
        $manager->setFullName('manager');
        $manager->setRoles(['ROLE_ADMIN']);
        $manager->setPlainPassword('manager'); // $manager->setPassword($this->passwordHasher->hashPassword($manager, 'manager'));
        $manager->setAvatar('https://picsum.photos/id/6/100/80');

        $entityManager->persist($manager);

        // Create user Role Agent
        for ($i = 1; $i < 3; $i++) {
            $agent[$i] = new User();
            $agent[$i]->setFullName('agent' . $i);
            $agentEmail = 'agent' . $i . '@serenity-estate.com';
            $agent[$i]->setemail($agentEmail);
            $agent[$i]->setRoles(['ROLE_AGENT']);
            // $agent[$i]->setPassword($this->passwordHasher->hashPassword($agent[$i], 'agent' . $i));
            $agent[$i]->setPlainPassword('agent');
            $agent[$i]->setAvatar('https://picsum.photos/id/1/100/95');

            $entityManager->persist($agent[$i]);
        }

        $viewer = new User();
        $viewer->setEmail('viewer@serenity-estate.com');
        $viewer->setFullName('viewer');
        $viewer->setRoles(['ROLE_VIEWER']);
        $viewer->setPlainPassword('viewer');
        // $viewer->setPassword($this->passwordHasher->hashPassword($viewer, '123456'));
        $viewer->setAvatar('https://picsum.photos/id/8/100/150');
        $entityManager->persist($viewer);


        $entityManager->flush();
    }


    public static function getGroups(): array
    {
        return ['users'];
    }
}
