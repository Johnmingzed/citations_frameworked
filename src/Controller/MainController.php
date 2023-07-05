<?php

/**
 * Nom du fichier : MainController.php
 * Description : Ce fichier contient la classe MainController, c'est le point d'entrée de l'application.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe MainController
 * Cette classe est le point d'entrée de l'application.
 */
class MainController extends AbstractController
{
    /**
     * Page d'accueil
     * Cette méthode redirige vers la page des citations.
     *
     * @return Response La réponse HTTP contenant la redirection vers la page des citations.
     */
    #[Route('/', name: 'app_main')]
    public function index(): Response
    {
        return $this->redirectToRoute('app_citations');
    }
}
