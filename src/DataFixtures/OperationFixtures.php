<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Operation;


class OperationFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct()
    {
    }

    public function load(ObjectManager $entityManager): void
    {
        // Create RealEstate Operation
        $operation1 = new Operation();
        $operation2 = new Operation();

        $operation1->setName("Location");

        $entityManager->persist($operation1);

        $operation2->setName("Vente");
        $entityManager->persist($operation2);


        $entityManager->flush();
    }


    public static function getGroups(): array
    {
        return ['operations'];
    }
}
