<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AgencyManagementController extends AbstractController
{
    #[Route('/agency', name: 'app_agency_management')]
    public function index(): Response
    {
        return $this->render('agency_management/index.html.twig', [
            'controller_name' => 'AgencyManagementController',
        ]);
    }
}