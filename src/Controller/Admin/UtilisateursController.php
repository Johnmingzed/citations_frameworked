<?php

/**
 * Nom du fichier : UtilisateursController.php
 * Description : Ce fichier contient la classe UtilisateursController qui affiche la liste des utisateurs.
 */

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur UtilisateursController
 * Gère les fonctionnalités liées aux utilisateurs dans la section d'administration.
 */
class UtilisateursController extends AbstractController
{
    /**
     * Page d'accueil des utilisateurs
     * Affiche la liste des utilisateurs dans la section d'administration.
     *
     * @return Response Vue Twig.
     */
    #[Route('/admin', name: 'app_utilisateurs')]
    public function index(): Response
    {
        return $this->render('utilisateurs/index.html.twig', [
            'controller_name' => 'UtilisateursController',
        ]);
    }
}
