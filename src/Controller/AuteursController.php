<?php

namespace App\Controller;

use App\Repository\AuteurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuteursController extends AbstractController
{
    #[Route('/auteurs', name: 'app_auteurs')]
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('auteurs/index.html.twig', [
            'controller_name' => 'AuteursController',
            'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc'])
        ]);
    }

    #[Route('/auteur/details/{id}', name: 'app_auteurs_detail')]
    public function detail(AuteurRepository $auteurRepository, int $id): Response
    {
        return $this->render('auteurs/detail.html.twig', [
            'controller_name' => 'AuteursController',
            'auteur' => $auteurRepository->find($id)
        ]);
    }

    #[Route('/auteur/suppression/{id}', name: 'app_auteurs_suppression')]
    public function suppression(AuteurRepository $auteurRepository, int $id): Response
    {
        $auteur = $auteurRepository->find($id);
        // Vérifier le résultat avant traitement
        $auteur_name = $auteur->getAuteur();
        $auteurRepository->remove($auteur, true);
        return $this->render('auteurs/index.html.twig', [
            'controller_name' => 'AuteursController',
            'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
            'message' => $auteur_name . ' a été effacé'
        ]);
    }
}
