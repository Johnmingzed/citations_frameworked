<?php

namespace App\Controller;

use App\Repository\AuteurRepository;
use App\Repository\CitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CitationsController extends AbstractController
{
    #[Route('/citations', name: 'app_citations')]
    public function index(CitationRepository $citationRepository, AuteurRepository $auteurRepository): Response
    {
        return $this->render('citations/index.html.twig', [
            'controller_name' => 'CitationsController',
            'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
            'citations' => $citationRepository->findBy([], ['citation' => 'asc'])
        ]);
    }
}
