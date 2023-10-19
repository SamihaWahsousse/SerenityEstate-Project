<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\PropertyType;


class PropertyTypeFixture extends Fixture implements FixtureGroupInterface
{
    public function __construct()
    {
    }

    public function load(ObjectManager $entityManager): void
    {
        // Create propertyType
        $propertyType1 = new PropertyType();
        $propertyType2 = new PropertyType();
        $propertyType3 = new PropertyType();

        $propertyType1->setName("Maison");

        $entityManager->persist($propertyType1);

        $propertyType2->setName("Appartement");
        $entityManager->persist($propertyType2);

        $propertyType3->setName("Studio");
        $entityManager->persist($propertyType3);

        $entityManager->flush();
    }


    public static function getGroups(): array
    {
        return ['propertyType'];
    }
}
