<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\CityRegion;
use App\Entity\Operation;
use App\Entity\Property;
use App\Entity\PropertyType as EntityPropertyType;
use App\Entity\User;
use App\Form\PropertyType;
use App\Repository\OperationRepository;
use App\Repository\PropertyRepository;
use App\Repository\PropertyTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    #[Route('/property/list', name: 'app_property_list')]
    public function index(PropertyRepository $propertyRepository, Request $request, PaginatorInterface $paginator): Response
    {
        /**
         * This controller display all properties
         * 
         */

        $properties = $paginator->paginate(
            $propertyRepository ->paginationQuery(),
            $request->query->get('page',1),
          5
        );
        return $this->render('pages/property/listProperties.html.twig', [
            'properties'=>$properties
        ]);
    }

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

            $propertyType = $entityManager->getRepository(EntityPropertyType::class)->findOneBy(  // Récupérer la propety type sélectionnée depuis la BD
                ['name' => $property->getPropertyType()->getName()]
            );

            $operation = $property->getOperation()->getName(); // Récupérer l'opération sélectionnée depuis la BD

            $operationQuery = $entityManager->getRepository(Operation::class)->findOneBy(
                ['name' => $operation]
            );

            $address = $property->getAddress();
            $cityRegionValue = $address->getCityRegion()->getCity(); //Récupérer la région et city envoyée par le formulaire
            $regionValue = $address->getCityRegion()->getRegion();

            $cityRegion = $entityManager->getRepository(CityRegion::class)->findOneBy( // Récupérer cityRegion depuis la BD
                ['city' => $cityRegionValue, 'region' => $regionValue]
            );

            if ($cityRegion != null) { //  If the CityRegion doesn't exist.
                $address->setCityRegion($cityRegion);

                if ($propertyType  != null) { // Mettre à jour la property type pour eviter son persist
                    $owner = $property->getOwner();
                    $owner = $entityManager->getRepository(User::class)->findOneBy(
                        ['email' => $owner->getEmail()]
                    );

                    if ($owner != null) {
                        if ($operationQuery != null) {
                            $property->setOperation($operationQuery);
                            $property->setOwner($owner);
                            $property->setPropertyType($propertyType);
                            $property->setAddress($address);
                            
                            $entityManager->persist($property);
                            $entityManager->flush();
                            
                            $this->addFlash('success', 'Property Created!'); //display a success message
                            return $this->redirectToRoute('app_property_list');
                        }
                    }
                }
            }
            //else envoyé un msg erreur 


        } else {  //else we display the form
            return $this->render('pages/property/add.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    }

    //show one property 
    #[Route('/property/{id}', name: 'show_property')]
    public function showProperty(ManagerRegistry $doctrine, $id): Response
    {

        $propertyRepository = $doctrine->getRepository(Property::class);
        $property = $propertyRepository->find($id);

        if (!$property) {
            $this->addFlash('error', 'The property : $id does not existe');
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
        // dd($form->getData());
        
        if ($form->isSubmitted() && $form->isValid()) {  //get the submitted data-if the form is submitted, we add the property object in the DB and redirect to list properties page
            $property = $form->getData();
            
            $entityManager ->persist($property);
            $entityManager->flush();   
            
            $this->addFlash('success', 'Property Updated!'); //display a success message
            return $this->redirectToRoute('app_property_list');
        }
            
        return $this->render('pages/property/edit.html.twig', [
            'form'=>$form->createView(),
        ]);
    }
}