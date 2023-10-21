<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\PropertyType as EntityPropertyType;
use App\Form\PropertyType;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    #[Route('/property', name: 'app_property_list')]
    public function index(): Response
    {
        return $this->render('pages/property/listProperties.html.twig');
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
            $operation = $entityManager->getRepository(EntityPropertyType::class)->findOneBy(
                ['name' => $property->getOperation()->getName()]
            );

            // Récupérer cityRegion depuis la BD
            // $cityRegion = $entityManager->getRepository(EntityPropertyType::class)->findOneBy(
            //     ['name' => $property->getAddress()->getCityRegion()->getName()]
            // );

            // Mettre à jour la property type pour eviter son persist
            if ($propertyType  != null) {
                $property->setPropertyType($propertyType);
                $property->setOperation($operation);
            }

            $entityManager->persist($property);
            $entityManager->flush();

            //display a success message
            $this->addFlash('success', 'Property Created!');
            return $this->redirectToRoute('app_home');

            //if the form is submitted, we add the property object in the DB and redirect to home page and 
            // dd($property);
            // $form->getData();
        } else {
            //else we display the form
            return $this->render('pages/property/add.html.twig', [
                'form' => $form
            ]);
        }
    }
}
