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
        // Create Admin
        $admin = new User();
        $admin->setEmail('Admin@serenity-estate.com');
        $admin->setFullName('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPlainPassword('Admin');
        $admin->setAvatar('https://picsum.photos/id/8/100/150');
        $entityManager->persist($admin);

        // Create Manager 
        $manager = new User();
        $manager->setEmail('Manager@serenity-estate.com');
        $manager->setFullName('Manager');
        $manager->setRoles(['ROLE_MANAGER']);
        $manager->setPlainPassword('Manager'); // $manager->setPassword($this->passwordHasher->hashPassword($manager, 'manager'));
        $manager->setAvatar('https://picsum.photos/id/6/100/80');
        $entityManager->persist($manager);

        // Create Agent
        for ($i = 1; $i < 3; $i++) {
            $agent[$i] = new User();
            $agent[$i]->setFullName('Agent' . $i);
            $agentEmail = 'Agent' . $i . '@serenity-estate.com';
            $agent[$i]->setemail($agentEmail);
            $agent[$i]->setRoles(['ROLE_AGENT']);
            // $agent[$i]->setPassword($this->passwordHasher->hashPassword($agent[$i], 'agent' . $i));
            $agent[$i]->setPlainPassword('Agent');
            $agent[$i]->setAvatar('https://picsum.photos/id/1/100/95');
            $entityManager->persist($agent[$i]);
        }
        $client = new User();
        $client->setEmail('Client@serenity-estate.com');
        $client->setFullName('Client');
        $client->setRoles(['ROLE_CLIENT']);
        $client->setPlainPassword('Client');
        $client->setAvatar('https://picsum.photos/id/8/100/150');
        $entityManager->persist($client);

        $entityManager->flush();
    }


    public static function getGroups(): array
    {
        return ['users'];
    }
}
