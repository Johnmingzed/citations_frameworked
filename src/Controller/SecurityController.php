<?php

/**
 * Nom du fichier : SecurityController.php
 * Description : Ce fichier contient la classe SecurityController qui gère la connexion des utilisateurs.
 */

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * Classe SecurityController
 * Ce contrôleur gère les fonctionnalités de sécurité, telles que la connexion et la déconnexion des utilisateurs.
 */
class SecurityController extends AbstractController
{
    /**
     * Page de connexion
     * Cette méthode affiche le formulaire de connexion et gère le processus de connexion des utilisateurs.
     *
     * @param AuthenticationUtils $authenticationUtils L'utilitaire d'authentification
     * @return Response La réponse HTTP
     */
    #[Route(path: '/connexion', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer l'erreur de connexion s'il y en a une
        $error = $authenticationUtils->getLastAuthenticationError();
        // Dernier nom d'utilisateur saisi par l'utilisateur
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * Déconnexion de l'utilisateur
     * Cette méthode gère la déconnexion de l'utilisateur.
     *
     * @throws \LogicException Cette méthode peut être vide - elle sera interceptée par la clé de déconnexion de votre pare-feu.
     */
    #[Route(path: '/deconnexion', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
