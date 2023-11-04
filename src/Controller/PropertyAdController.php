<?php

namespace App\Controller;

use App\Entity\Property;
use App\Entity\Propertyad;
use App\Form\PropertyAdForm;
use App\Repository\PropertyadRepository;
use App\Repository\PropertyRepository;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyAdController extends AbstractController
{
     #[Route('/propertyAd/list', name: 'app_propertyAd_list')]
      public function index(PropertyadRepository $propertyadRepository, Request $request, PaginatorInterface $paginator): Response
    {
        /**
         * This controller display all propertiesADS
         * 
         */

            $propertyAd = $paginator->paginate(
            $propertyadRepository ->paginationQuery(),
            $request->query->get('page',1),
          5
        );
        // dd($propertyAd);
        return $this->render('pages/propertyAd/listPropertyAd.html.twig', [
            'propertyAd'=>$propertyAd
        ]);
    }


    #[Route('/propertyAd/add', name: 'add_propertyAd', methods: ['GET', 'POST'])]
    public function addPropertyAd(Request $request, ManagerRegistry $doctrine, PropertyRepository $propertyRepo, ?int $IdProperty): Response
    {
        
        $propertyAd = new Propertyad();
        $propertyrelated = $propertyRepo->findOneBy(['id' => $IdProperty]);
        
        if($IdProperty) {
            $propertyAd->setPropertyRef($propertyRepo->findOneBy(['id' => $IdProperty]));
        }
        $form = $this->createForm(PropertyAdForm::class, $propertyAd, ['action' => $this->generateUrl('add_propertyAd')]);
        $form->remove("createdAt"); //remove the createdAt from the form it will be generated automatically
        $form->remove("updatedAt"); //remove the updatedAt from the form it will be generated automatically              
        $form->handleRequest($request); //handle the request 
        
        if ($form->isSubmitted() && $form->isValid()) {
   
            $entityManager = $doctrine->getManager();
            if ($propertyAd != null) {
               
                $entityManager->persist($propertyAd);
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

        

    #[Route('/propertyAd/edit/{id}', name: 'edit_propertyAd', methods: ['GET', 'POST'])]
    public function editPropertyAd(PropertyadRepository $propertyadRepository,int $id,Request $request,EntityManagerInterface $entityManager,): Response
    {
        $propertyAd = $propertyadRepository->find(['id' => $id]);

        $form = $this->createForm(PropertyAdForm::class, $propertyAd ,['action' => $this->generateUrl('edit_propertyAd', ['id' => $id])]);
        
        $form->remove("createdAt"); //remove the createdAt from the form it will be generated automatically

        $form->handleRequest($request); //handle the request 
        
        if ($form->isSubmitted() && $form->isValid()) {  //get the submitted data-if the form is submitted, we add the propertyAd object in the DB and redirect to list properties page
            $propertyAd = $form->getData();
            $newupdatedAt= new DateTimeImmutable('now');//set new updatedAt date if the propertyAd is edited
            $propertyAd->setUpdatedAt($newupdatedAt);
            $entityManager ->persist($propertyAd);
            $entityManager->flush();   

            $this->addFlash('success', 'PropertyAd Updated!'); //display a success message
            return $this->redirectToRoute('app_home');
        }
            
        return $this->render('pages/propertyAd/editAd.html.twig', [
            'edit_form'=>$form->createView(),
        ]);


   


    
    }
}


















        
      