<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\CityRegion;
use App\Entity\Operation;
use App\Entity\Property;
use App\Entity\Propertyad;
use App\Entity\PropertyType as EntityPropertyType;
use App\Entity\User;
use App\Form\PropertyAdForm;
use App\Form\PropertyType;
use App\Repository\OperationRepository;
use App\Repository\PropertyRepository;
use App\Repository\PropertyTypeRepository;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class PropertyController extends AbstractController
{
    
#[IsGranted('IS_AUTHENTICATED_FULLY')]
// This controller display all properties
    #[Route('/property/list', name: 'app_property_list')]
    public function index(PropertyRepository $propertyRepository, Request $request, PaginatorInterface $paginator): Response
    {       
            $properties = $paginator->paginate(
            $propertyRepository ->paginationQuery(),
            $request->query->get('page',1),
          8
        );
        return $this->render('pages/property/listProperties.html.twig', [
            'properties'=>$properties
        ]);
    }
    
// This controller Add a new property
#[IsGranted('ROLE_MANAGER')]
    #[Route('/property/add', name: 'app_property_add', methods: ['GET', 'POST'])]
    public function addProperty(Request $request, ManagerRegistry $doctrine): Response
    {
        $property = new Property();
    
        $form = $this->createForm(PropertyType::class, $property);
        $form->remove("createdAt"); //remove the createdAt from the form it will be generated automatically
        $form->remove("updatedAt"); //remove the updatedAt from the form it will be generated automatically

        $form->handleRequest($request); //handle the request 
      
        if ($form->isSubmitted() && $form->isValid()) {  //get the submitted data-if the form is submitted, we add the property object in the DB and redirect to list properties page
            $entityManager = $doctrine->getManager();

            $propertyType = $entityManager->getRepository(EntityPropertyType::class)->findOneBy(  // Retreive the selected propertyType from the database
                ['name' => $property->getPropertyType()->getName()]
            );

            $operation = $property->getOperation()->getName(); // Retreive the selected operation from the database 

            $operationQuery = $entityManager->getRepository(Operation::class)->findOneBy(
                ['name' => $operation]
            );

            $address = $property->getAddress();                      // Retreive the selected "Address" from the submitted form 
            $cityRegionValue = $address->getCityRegion()->getCity(); // get the selected "région and city" from the address 
            $regionValue = $address->getCityRegion()->getRegion();

            $cityRegion = $entityManager->getRepository(CityRegion::class)->findOneBy( // Search for the selected "region city" from the database
                ['city' => $cityRegionValue, 'region' => $regionValue]
            );

            if ($cityRegion != null) { //  if the CityRegion exist in database.
                $address->setCityRegion($cityRegion);

                if ($propertyType  != null) { // Update the property type to avoid it's persist (avoid creating a new PropertyType object as a row in Database)
                    $owner = $property->getOwner();
                    $owner = $entityManager->getRepository(User::class)->findOneBy(['email' => $owner->getEmail()]);

                    if ($owner != null) {
                        if ($operationQuery != null) {
                            $property->setOperation($operationQuery);
                            $property->setOwner($owner);
                            $property->setPropertyType($propertyType);
                            $property->setAddress($address);
                            
                            $entityManager->persist($property);
                            $entityManager->flush();
                            
                            $this->addFlash('success', 'Property Created !'); //display a success message
                            return $this->redirectToRoute('app_property_list');
                            
                        } else {
                        
                            $this->addFlash('info', 'Operation does not exist !'); //display info message
                        }
                        
                    } else {
                            $this->addFlash('info', 'Owner does not exist !'); //display info message
                    }
                    
                } else {
                            $this->addFlash('info', 'Property type does not exist !'); //display info message
                }
               
            } else {
                             $this->addFlash('failed',"Your Address field is empty ! "); //Display error cityRegion does not exist, Contact Administrator
            }

        } // else {  //else we display the form
        //afficher un message que le form n'est pas valide 
        
            return $this->render('pages/property/add.html.twig', [
                'form' => $form->createView(),
            ]);
        //}
    }


    //Show one property 
    #[Route('/property/{id}', name: 'show_property')]
    public function showProperty(Request $request, ManagerRegistry $doctrine, $id): Response
    {
        $propertyRepository = $doctrine->getRepository(Property::class);
        $property = $propertyRepository->find($id);
        
        if (!$property) {
            $this->addFlash('error', 'The Property N° ' . $id . ' does not existe');
            return $this->redirectToRoute('app_property_list');
            return $this->redirectToRoute('app_property_list');
        }
        return $this->render('pages/property/showOneProperty.html.twig', [
            'property' => $property
        ]);
    }

    //Edit property 
    #[Route('/property/edit/{id}', name: 'edit_property', methods: ['GET', 'POST'])]
    public function editProperty(PropertyRepository $propertyRepository, int $id,Request $request,EntityManagerInterface $entityManager): Response
    {
        $property = $propertyRepository->findOneBy(['id' => $id]);
        $form = $this->createForm(PropertyType::class,$property, ['data' => $property]);
        
        $form->remove("createdAt"); //remove the createdAt from the form it will be generated automatically

        $form->handleRequest($request); //handle the request 
        
        if ($form->isSubmitted() && $form->isValid()) {  //get the submitted data-if the form is submitted, we add the property object in the DB and redirect to list properties page
            $property = $form->getData();
            
            $newupdatedAt= new DateTimeImmutable('now');//set new updatedAt date if the propertyAd is edited
            $property->setUpdatedAt($newupdatedAt);
            
            $entityManager ->persist($property);
            $entityManager->flush();   
            
            $this->addFlash('success', 'Property N° '. $id . ' Updated Successfully !'); //display a success message
            return $this->redirectToRoute('app_property_list');
        }
            
        return $this->render('pages/property/edit.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}