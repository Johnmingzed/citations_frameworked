<?php

/**
 * Nom du fichier : UtilisateursController.php
 * Description : Ce fichier contient la classe UtilisateursController qui gère les actions sur les utilisateurs.
 */

namespace App\Controller;

use App\Form\UtilisateurFormType;
use App\Repository\UtilisateurRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Classe UtilisateursController
 * Ce contrôleur gère les actions liées aux utilisateurs.
 */
class UtilisateursController extends AbstractController
{

    private $utilisateurRepository;

    /**
     * Initialisation de la classe avec injection de dépendance
     * 
     * @param UtilisateurRepository $utilisateurRepository Le repository des auteurs.
     */
    public function __construct(UtilisateurRepository $utilisateurRepository)
    {
        $this->utilisateurRepository = $utilisateurRepository;
    }

    /**
     * Page d'index des utilisateurs
     * Cette méthode affiche la liste des utilisateurs.
     *
     * @return Response La réponse HTTP
     */
    #[Route('/utilisateurs', name: 'app_utilisateurs')]
    public function index(): Response
    {
        return $this->render('utilisateurs/index.html.twig', [
            'controller_name' => 'UtilisateursController',
            'utilisateurs' => $this->utilisateurRepository->findBy([], ['mail' => 'asc'])
        ]);
    }

    /**
     * Modification d'un utilisateur
     * Cette méthode permet de modifier les informations d'un utilisateur existant.
     *
     * @param int $id L'ID de l'utilisateur à modifier
     * @param Request $request La requête HTTP
     * @return Response La réponse HTTP
     */
    #[Route('/utilisateur/modifier/{id}', name: 'app_utilisateurs_modifier')]
    public function modification(int $id, Request $request): Response
    {
        $utilisateur = $this->utilisateurRepository->find($id);
        if (!$utilisateur) {
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_utilisateurs');
        }
        $form = $this->createForm(UtilisateurFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Utilisateur modifié avec succès.';
            $message_type = 'success';

            try {
                $this->utilisateurRepository->save($utilisateur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Retour sur la page d'index
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_utilisateurs');
        }

        return $this->render('utilisateurs/modifier.html.twig', [
            'controller_name' => 'utilisateursController',
            'utilisateur' => $utilisateur,
            'utilisateurForm' => $form->createView()
        ]);
    }

    /**
     * Suppression d'un utilisateur
     * Cette méthode permet de supprimer un utilisateur existant.
     *
     * @param int $id L'ID de l'utilisateur à supprimer
     * @return Response La réponse HTTP
     */
    #[Route('/utilisateur/supprimer/{id}', name: 'app_utilisateurs_supprimer')]
    public function suppression(int $id): Response
    {
        $utilisateur = $this->utilisateurRepository->find($id);
        if (!$utilisateur) {
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_utilisateurs');
        }

        $utilisateur_mail = $utilisateur->getMail();
        $this->utilisateurRepository->remove($utilisateur, true);

        // Retour sur la page d'index
        $this->addFlash('success', $utilisateur_mail . ' a été effacé');
        return $this->redirectToRoute('app_utilisateurs');
    }

    /**
     * Réinitialisation du mot de passe d'un utilisateur
     * Cette méthode permet de réinitialiser le mot de passe d'un utilisateur.
     *
     * @param int $id L'ID de l'utilisateur pour lequel réinitialiser le mot de passe
     * @return Response La réponse HTTP
     */
    #[Route('/utilisateur/resetpw/{id}', name: 'app_utilisateurs_reset_pw')]
    public function resetPW(int $id): Response
    {
        // Récupération de l'utilisateur
        $utilisateur = $this->utilisateurRepository->find($id);
        if (!$utilisateur) {
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_utilisateurs');
        }

        $utilisateur_mail = $utilisateur->getMail();
        $utilisateur->setPassword(null);
        $this->utilisateurRepository->save($utilisateur, true);

        // Retour sur la page d'index
        $this->addFlash('success', 'Le mot de passe associé à ' . $utilisateur_mail . ' a été réinitialisé.');
        return $this->redirectToRoute('app_utilisateurs');
    }
}
