<?php

/**
 * Nom du fichier : AuteursController.php
 * Description : Ce fichier contient la classe AuteursController qui gère les fonctionnalités des auteurs.
 */

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurFormType;
use App\Repository\AuteurRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur AuteurController
 * Gère les fonctionnalités CRUD liées aux auteurs.
 */
class AuteursController extends AbstractController
{
    private $auteurRepository;

    /**
     * Initialisation de la classe avec injection de dépendance
     * 
     * @param AuteurRepository $auteurRepository Le repository des auteurs.
     */
    public function __construct(AuteurRepository $auteurRepository)
    {
        $this->auteurRepository = $auteurRepository;
    }

    /**
     * Affiche la liste des auteurs par ordre alphabétique.
     * 
     * @return Response Vue Twig affichant la liste des auteurs.
     */
    #[Route('/auteurs', name: 'app_auteurs')]
    public function index(): Response
    {
        return $this->render('auteurs/index.html.twig', [
            'controller_name' => 'AuteursController',
            'auteurs' => $this->auteurRepository->findBy([], ['auteur' => 'asc'])
        ]);
    }

    /**
     * Affiche les détails d'un auteur sélectionné par son ID.
     * 
     * @param int $id L'ID d'un auteur.
     * @return Reponse Vue Twig affichant les détails d'un auteur.
     */
    #[Route('/auteur/details/{id}', name: 'app_auteurs_detail')]
    public function detail(int $id): Response
    {
        if (!$this->auteurRepository->find($id)) {
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_auteurs');
        }
        return $this->render('auteurs/detail.html.twig', [
            'controller_name' => 'AuteursController',
            'auteur' => $this->auteurRepository->find($id)
        ]);
    }

    /**
     * Modifie un auteur existant sélectionné par son ID.
     * 
     * @param int $id L'ID d'un auteur.
     * @param Request $request Modifications à apporter à l'auteur, transmises via POST.
     * @return Reponse Vue Twig affichant un formulaire de modification ou un message.
     */
    #[Route('/auteur/modifier/{id}', name: 'app_auteurs_modifier')]
    public function modification(int $id, Request $request): Response
    {
        // Récupération de l'auteur via son ID
        $auteur = $this->auteurRepository->find($id);

        // Retour sur la page d'index en cas de référence inexistante
        if (!$auteur) {
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_auteurs');
        }

        // Initialisation du formulaire de modification prérempli
        $form = $this->createForm(AuteurFormType::class, $auteur);
        $form->handleRequest($request);

        // Enregistrement des modifications en cas soumission
        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Auteur modifié avec succès.';
            $message_type = 'success';

            try {
                $this->auteurRepository->save($auteur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Retour sur la page d'index avec affichage d'un message
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_auteurs', [
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        // Affichage du formulaire de modification prérempli
        return $this->render('auteurs/modifier.html.twig', [
            'controller_name' => 'AuteursController',
            'auteur' => $auteur,
            'auteurForm' => $form->createView()
        ]);
    }

    /**
     * Ajoute un nouvel auteur.
     *
     * @param Request $request Informations sur l'auteur à créer transmises via POST.
     * @return Reponse Vue Twig affichant un formulaire de création ou un message.
     */
    #[Route('/auteur/ajouter/', name: 'app_auteurs_ajouter')]
    public function ajouter(Request $request): Response
    {
        // Création d'un entité auteur
        $auteur = new Auteur();

        // Initialisation du formulaire de création
        $form = $this->createForm(AuteurFormType::class, $auteur);
        $form->handleRequest($request);

        // Enregistrement des informations en cas soumission
        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Nouvel auteur créé avec succès.';
            $message_type = 'success';

            try {
                $this->auteurRepository->save($auteur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Retour sur la page d'index avec affichage d'un message
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_auteurs', [
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        // Affichage du formulaire de création
        return $this->render('auteurs/ajouter.html.twig', [
            'controller_name' => 'AuteursController',
            'auteurForm' => $form->createView()
        ]);
    }

    /**
     * Supprime un auteur sélectionné par son ID.
     * 
     * @param int $id L'ID d'un auteur.
     * @return Reponse Vue Twig affichant un message.
     */
    #[Route('/auteur/supprimer/{id}', name: 'app_auteurs_supprimer')]
    public function suppression(int $id): Response
    {
        // Récupération de l'auteur via son ID
        $auteur = $this->auteurRepository->find($id);

        // Retour sur la page d'index avec affichage d'un message si l'auteur n'existe pas
        if (!$auteur) {
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_auteurs');
        }

        // Suppresion de l'auteur
        $auteur_name = $auteur->getAuteur(); // Stockage du nom de l'auteur 
        $this->auteurRepository->remove($auteur, true);

        // Retour sur la page d'index avec affichage d'un message de réussite
        $this->addFlash('success', $auteur_name . ' a été effacé.');
        return $this->redirectToRoute('app_auteurs');
    }
}
