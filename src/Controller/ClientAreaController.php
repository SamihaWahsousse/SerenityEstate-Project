<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ClientAreaController extends AbstractController
{
    #[Route('/client', name: 'app_client_area')]
    public function index(): Response
    {
        return $this->render('client_area/index.html.twig', [
            'controller_name' => 'ClientAreaController',
        ]);
    }
}