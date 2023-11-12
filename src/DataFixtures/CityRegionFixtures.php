<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use App\Entity\CityRegion;


class CityRegionFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct()
    {
    }

    public function load(ObjectManager $entityManager): void
    {
        // Create Admin
        $cityRegion1 = new CityRegion();
        $cityRegion2 = new CityRegion();

        $cityRegion1->setCity("Grenoble");
        $cityRegion1->setRegion("Auvergne-Rhone-Alpes");
        $entityManager->persist($cityRegion1);

        $cityRegion2->setCity("Lyon");
        $cityRegion2->setRegion("Auvergne-Rhone-Alpes");
        $entityManager->persist($cityRegion2);


        $entityManager->flush();
    }


    public static function getGroups(): array
    {
        return ['cityRegion'];
    }
}
