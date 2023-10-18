<?php

namespace App\Controller;

use App\Entity\Property;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function addProperty(): Response
    {
        $property = new Property();

        return $this->render('pages/property/add.html.twig');
    }
}
