<?php

/**
 * Nom du fichier : CitationsController.php
 * Description : Ce fichier contient la classe RegistrationController qui gère la création des utilisateurs.
 */

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\RegistrationFormType;
use App\Repository\UtilisateurRepository;
use App\Security\AuthentificationAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

/**
 * Classe RegistrationController
 * Ce contrôleur gère la création des utilisateurs.
 */
class RegistrationController extends AbstractController
{
    /**
     * Page d'inscription
     * Cette méthode permet de créer un compte d'utilisateur.
     *
     * @param Request $request La requête HTTP
     * @param EntityManagerInterface $entityManager L'interface de gestion des entités
     * @return Response La réponse HTTP
     */
    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, EntityManagerInterface $entityManager): Response
    {
        $user = new Utilisateur();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email
            $this->addFlash('success', 'Le nouvel utilisateur' . $user->getMail() . ' a bien été créé.');
            return $this->redirectToRoute('app_utilisateurs');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
