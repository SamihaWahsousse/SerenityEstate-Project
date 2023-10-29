<?php

namespace App\Controller;

use App\Entity\Address;
use App\Entity\CityRegion;
use App\Entity\Operation;
use App\Entity\Property;
use App\Entity\PropertyType as EntityPropertyType;
use App\Entity\User;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
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

        $pagination = $paginator->paginate(
            $propertyRepository ->paginationQuery(),
            $request->query->get('page',1),
          5
        );
        // $properties = $propertyRepository->findAll();
        return $this->render('pages/property/listProperties.html.twig', [
            // 'properties' => $properties
            'pagination'=>$pagination
        ]);
    }

    #[Route('/property/add', name: 'app_property_add', methods: ['GET', 'POST'])]
    public function addProperty(Request $request, ManagerRegistry $doctrine): Response
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);


        //remove the createdAt from the form it will be generated automatically
        $form->remove("createdAt");

        //handle the request 
        $form->handleRequest($request);
        //get the submitted data
        if ($form->isSubmitted()) {
            $entityManager = $doctrine->getManager();

            // Récupérer la propety type sélectionnée depuis la BD
            $propertyType = $entityManager->getRepository(EntityPropertyType::class)->findOneBy(
                ['name' => $property->getPropertyType()->getName()]
            );

            // Récupérer l'opération sélectionnée depuis la BD
            $operation = $property->getOperation()->getName();

            $operationQuery = $entityManager->getRepository(Operation::class)->findOneBy(
                ['name' => $operation]
            );
            // dd($operationQuery);

            $address = $property->getAddress();
            //Récupérer la région et city envoyée par le formulaire
            $cityRegionValue = $address->getCityRegion()->getCity();
            $regionValue = $address->getCityRegion()->getRegion();

            // Récupérer cityRegion depuis la BD
            $cityRegion = $entityManager->getRepository(CityRegion::class)->findOneBy(
                ['city' => $cityRegionValue, 'region' => $regionValue]
            );

            //  If the CityRegion doesn't exist.
            if ($cityRegion != null) {


                $address->setCityRegion($cityRegion);

                // Mettre à jour la property type pour eviter son persist
                if ($propertyType  != null) {

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
                            //display a success message
                            $this->addFlash('success', 'Property Created!');
                            return $this->redirectToRoute('app_property_list');
                        }
                    }
                }
            }
            //else envoyé un msg erreur 


            //if the form is submitted, we add the property object in the DB and redirect to home page and 
            // dd($property);
            // $form->getData();
        } else {
            //else we display the form
            // return $this->render('pages/property/add.html.twig', [
            //     'form' => $form->createView(),
            // ]);


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
}