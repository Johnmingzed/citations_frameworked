<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CitationsController extends AbstractController
{
    #[Route('/citations', name: 'app_citations')]
    public function index(): Response
    {
        return $this->render('citations/index.html.twig', [
            'controller_name' => 'CitationsController',
        ]);
    }
}
