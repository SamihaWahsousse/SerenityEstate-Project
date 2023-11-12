<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Property;
use App\Entity\Address;
use App\Entity\CityRegion;
use App\Entity\Operation;
use App\Entity\User;
use App\Entity\PropertyType;

class PropertyEntityTest extends TestCase
{
    public function testPropertyEntity() {              

        $region = 'Auvergne-Rhone-Alpes';
        $city = 'Grenoble';
        $street = '15 rue de test';
        $floor = 3;
        $currentTime = new \DateTimeImmutable();
        $operationName = 'Location';
        $ownerFullName = 'Owner Test';
        $ownerEmail = 'test@gmail.com';
        $ownerRole = ['ROLE_VIEWER', 'ROLE_OWNER'];
        $propertyTypeName = 'Maison';
        $description = 'test description';
        $availability = true;
        $rooms = 5;
        $price = 700;
        $area = 50;

        $cityRegion = new CityRegion();
        $cityRegion->setRegion($region);
        $cityRegion->setCity($city);

        $address = new Address();
        $address->setStreet($street);
        $address->setFloorNumber($floor);
        $address->setCityRegion($cityRegion);

        $owner = new User();
        $owner->setFullName($ownerFullName);
        $owner->setEmail($ownerEmail);
        $owner->setRoles($ownerRole);

        $operation = new Operation();
        $operation->setName($operationName);
        
        $propertyType = new PropertyType();
        $propertyType->setName($propertyTypeName);

        $property = new Property();
        $property->setDescription($description);
        $property->setIsAvailable($availability);
        $property->setRooms($rooms);
        $property->setPrice($price);
        $property->setArea($area);
        $property->setCreatedAt($currentTime);
        $property->setUpdatedAt($currentTime);
        $property->setAddress($address);
        $property->setOperation($operation);
        $property->setOwner($owner);
        $property->setPropertyType($propertyType);

        // Assertions
        $this->assertEquals($description, $property->getDescription());
        $this->assertEquals($availability, $property->isAvailable());
        $this->assertEquals($rooms, $property->getRooms());
        $this->assertEquals($price, $property->getPrice());
        $this->assertEquals($area, $property->getArea());        
        $this->assertEquals($currentTime, $property->getCreatedAt());
        $this->assertEquals($currentTime, $property->getUpdatedAt());
        $this->assertEquals($ownerFullName, $property->getOwner()->getFullName());
        $this->assertEquals($ownerEmail, $property->getOwner()->getEmail());
        $this->assertEquals($ownerRole, $property->getOwner()->getRoles());
        $this->assertEquals($propertyTypeName, $property->getPropertyType()->getName());
        $this->assertEquals($operationName, $property->getOperation()->getName());
        $this->assertEquals($street, $property->getAddress()->getStreet());
        $this->assertEquals($floor, $property->getAddress()->getFloorNumber());
        $this->assertEquals($region, $property->getAddress()->getCityRegion()->getRegion());
        $this->assertEquals($city, $property->getAddress()->getCityRegion()->getCity());        
    }
}