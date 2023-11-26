<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use App\Entity\Property;
use App\Entity\Address;
use App\Entity\CityRegion;
use App\Entity\Operation;
use App\Entity\User;
use App\Entity\PropertyType;

class PropertyFunctionalTest extends WebTestCase
{
    public function testResetPasswordPage()
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'Login');    
    }

    /*
    public function testPropertyCreationForm() {              
        
        $client->submitForm('Submit', [
            'comment_form[author]' => 'Fabien',
            'comment_form[text]' => 'Some feedback from an automated functional test',
            'comment_form[email]' => 'me@automat.ed',
            'comment_form[photo]' => dirname(__DIR__, 2).'/public/images/under-construction.gif',
        ]);

        $region = 'Auvergne-Rhone-Alpes';
        $city = 'Grenoble';
        $street = '15 rue de test';
        $floor = 3;
        $curentTime = new \DateTimeImmutable();
        $operationName = 'Location';
        $ownerFullName = 'Owner Test';
        $ownerEmail = 'test@gmail.com';
        $ownerRole = ['ROLE_OWNER'];
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
        $property->setCreatedAt($curentTime);
        $property->setUpdatedAt($curentTime);
        $property->setAddress($address);
        $property->setOperation($operation);
        $property->setOwner($owner);

        // Assertions
        $this->assertEquals($description, $property->getDescription());
        // $this->assertEquals('Michel Jakson', $property->getSingerName());
        // $this->assertEquals('test_cover.jpg', $property->getCover());
        // $this->assertEquals('test_audio.mp3', $property->getAudio());
    }*/



    // public function testLoginUser(): void
    // {
    //       $client = static::createClient();
    //       $client->request('GET', '/admin?crudAction=index&crudControllerFqcn=App%5CController%5CAdmin%5CUserCrudController');
    //       $client->followRedirects();
          
    //       $this->assertSelectorTextSame('h1', 'SerenityEstate - Users administration');

    //     //$this->assertPageTitleSame('SerenityEstate - Users administration');
    //       $client->clickLink('Add User');
          
    //     //   $this->assertPageTitleSame('Create User');
    //       $this->assertSelectorTextSame('h1', 'Create User');
          
    //       $client->submitForm('Create', [
    //           'User[email]' => 'samiha@gmail.com',
    //           'User[fullName]' => 'Samiha wahsousse',
    //           'User[plainPassword][first]' =>'Password1',
    //           'User[plainPassword][second]'=>'Password1',
    //           'User[roles][]'=>'ROLE_CLIENT'
            
    //       ]);
          
    //       $this->assertSelectorTextContains('h1', 'SerenityEstate - Users administration');
    // }
}





    