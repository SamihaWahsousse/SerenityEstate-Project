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
        $admin->setPlainPassword('Admin');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setAvatar('https://picsum.photos/id/8/100/150');
        $admin->setPhoneNumber('+33 777 11 11 11');
        $admin->setIsActive(true);
        $entityManager->persist($admin);

        // Create Manager 
        $manager = new User();
        $manager->setEmail('Manager@serenity-estate.com');
        $manager->setFullName('Manager');
        $manager->setRoles(['ROLE_MANAGER']);
        $manager->setPlainPassword('Manager');
        $manager->setAvatar('https://picsum.photos/id/6/100/80');
        $manager->setPhoneNumber('+33 666 22 22 22');
        $manager->setIsActive(true);

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
            $agent[$i]->setPhoneNumber('+33 555 88 88 88');
            $agent[$i]->setIsActive(true);
            $entityManager->persist($agent[$i]);
        }

        // Create Client
        $client = new User();
        $client->setEmail('Client@gmail.com');
        $client->setFullName('Client');
        $client->setRoles(['ROLE_CLIENT']);
        $client->setPlainPassword('Client');
        $client->setAvatar('https://picsum.photos/id/8/100/150');
        $client->setPhoneNumber('+33 353 99 99 99');
        $client->setIsActive(false);
        $entityManager->persist($client);

        $entityManager->flush();
    }


    public static function getGroups(): array
    {
        return ['users'];
    }
}