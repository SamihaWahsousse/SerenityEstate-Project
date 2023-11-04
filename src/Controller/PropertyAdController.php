<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\Propertyad;
use App\Form\PropertyAdForm;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyAdController extends AbstractController
{
    #[Route('/propertyAd', name: 'app_property_ad')]
    public function index(): Response
    {
        return $this->render('property_ad/index.html.twig', [
            'controller_name' => 'PropertyAdController',
        ]);
    }

    #[Route('/propertyAd/add', name: 'add_propertyAd', methods: ['GET', 'POST'])]
    public function addPropertyAd(Request $request, ManagerRegistry $doctrine, PropertyRepository $propertyRepo, ?int $IdProperty): Response
    {
        
        $propertyAd = new Propertyad();
        if($IdProperty) {
            $propertyAd->setProperty($propertyRepo->findOneBy(['id' => $IdProperty]));
        }
        $form = $this->createForm(PropertyAdForm::class, $propertyAd, ['action' => $this->generateUrl('add_propertyAd')]);
        $form->remove("createdAt"); //remove the createdAt from the form it will be generated automatically
        $form->remove("updatedAt"); //remove the updatedAt from the form it will be generated automatically              
        $form->handleRequest($request); //handle the request 
        
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($request->request);
            $entityManager = $doctrine->getManager();
            // $selectedProperty = $propertyRepo->findOneBy(  // Récupérer la propety sélectionnée depuis la BD
            //     ['id' => $requestPropertyId]);
            
            if ($propertyAd != null) {
               // $propertyAd->setProperty($selectedProperty);              
                $entityManager ->persist($propertyAd);
                $entityManager->flush();
                
                $this->addFlash('success', 'Property Ads Created !');
            return $this->redirectToRoute('app_property_list'); 
                      
            } else {
             dd('test');
        }
        }
        return $this->render('pages/propertyAd/addAd.html.twig', [
                'propertyAd'=>$propertyAd,
                'form' => $form->createView(),
            ]);
        }
        }
    