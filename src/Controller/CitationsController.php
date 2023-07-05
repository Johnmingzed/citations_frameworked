<?php

/**
 * Nom du fichier : CitationsController.php
 * Description : Ce fichier contient la classe CitationsController qui gère les fonctionnalités des citations.
 */

namespace App\Controller;

use App\Entity\Citation;
use App\Form\CitationFormType;
use App\Repository\AuteurRepository;
use App\Repository\CitationRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur CitationsController
 * Gère les fonctionnalités CRUD liées aux citations.
 */
class CitationsController extends AbstractController
{
    private $citationRepository;
    private $citations;
    private $auteurRepository;

    /**
     * Initialisation de la classe avec injection des dépendances
     * 
     * @param CitationRepository $citationRepository Le repository des citations.
     * @param AuteurRepository $auteurRepository Le repository des auteurs.
     */
    public function __construct(CitationRepository $citationRepository, AuteurRepository $auteurRepository)
    {
        $this->auteurRepository = $auteurRepository;
        $this->citationRepository = $citationRepository;

        // Récupération des citations classées par auteur
        $this->citations = $citationRepository->createQueryBuilder('c')
            ->leftJoin('c.auteur_id', 'a')
            ->orderBy('a.auteur', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Affiche la liste des citations regroupées par auteur
     * 
     * @return Response Vue Twig affichant la liste des citations classées par auteur.
     */
    #[Route('/citations', name: 'app_citations')]
    public function index(): Response
    {
        return $this->render('citations/index.html.twig', [
            'controller_name' => 'CitationsController',
            'auteurs' => $this->auteurRepository->findBy([], ['auteur' => 'asc']),
            'citations' => $this->citations
        ]);
    }

    /**
     * Affiche les détails d'une citation sélectionnée par son ID
     * 
     * @param int $id ID de la citation.
     * @return Réponse Vue Twig affichant les détails de la citation.
     */
    #[Route('/citation/details/{id}', name: 'app_citations_detail')]
    public function detail(int $id): Response
    {
        // Retour sur la page d'index avec un message d'erreur en cas de citation introuvable
        if (!$this->citationRepository->find($id)) {
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_citations');
        }
        // Appel de la vue détaillée
        return $this->render('citations/detail.html.twig', [
            'controller_name' => 'CitationsController',
            'citation' => $this->citationRepository->find($id)
        ]);
    }

    /**
     * Modifie une citation existante sélectionnée par son ID.
     * 
     * @param int $id L'ID de la citation.
     * @param Request $request Modifications à apporter à la citation, transmises via POST.
     * @return Reponse Vue Twig affichant un formulaire de modification ou un message.
     */
    #[Route('/citation/modifier/{id}', name: 'app_citations_modifier')]
    public function modification(int $id, Request $request): Response
    {
        // Récupération de la citation
        $citation = $this->citationRepository->find($id);

        // Récupération de la liste des auteurs
        $auteurs = $this->auteurRepository->findBy([], ['auteur' => 'ASC']);

        // Retour sur la page d'index avec un message d'erreur en cas de citation introuvable
        if (!$citation) {
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_citations');
        }

        // Initialisaiton du formulaire de modification prérempli
        $form = $this->createForm(CitationFormType::class, $citation, ['auteur' => $auteurs]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Citation modifiée avec succès.';
            $message_type = 'success';

            try {
                $this->citationRepository->save($citation, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Retour sur la page d'index avec affichage d'un message
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_citations');
        }

        // Affichage du formulaire de modification prérempli
        return $this->render('citations/modifier.html.twig', [
            'controller_name' => 'CitationsController',
            'citation' => $citation,
            'citationForm' => $form->createView()
        ]);
    }

    /**
     * Ajoute une nouvelle citation.
     *
     * @param Request $request Informations sur la citation à créer, transmises via POST.
     * @return Reponse Vue Twig affichant un formulaire de création ou un message.
     */
    #[Route('/citation/ajouter/', name: 'app_citations_ajouter')]
    public function ajouter(Request $request): Response
    {
        // Création d'une entité citation
        $citation = new Citation();

        // Récupération de la liste des auteurs
        $auteurs = $this->auteurRepository->findBy([], ['auteur' => 'ASC']);

        // Initialisation du formulaire de création
        $form = $this->createForm(CitationFormType::class, $citation, ['auteur' => $auteurs]);
        $form->handleRequest($request);

        // Enregistrement des informations en cas soumission
        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Nouvelle citation créée avec succès.';
            $message_type = 'success';

            try {
                $this->citationRepository->save($citation, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Retour sur la page d'index avec affichage d'un message
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_citations');
        }

        // Affichage du formulaire de création
        return $this->render('citations/ajouter.html.twig', [
            'controller_name' => 'CitationsController',
            'citationForm' => $form->createView()
        ]);
    }

    /**
     * Supprime une citation sélectionnée par son ID.
     * 
     * @param int $id L'ID d'une citation.
     * @return Reponse Vue Twig affichant un message.
     */
    #[Route('/citation/supprimer/{id}', name: 'app_citations_supprimer')]
    public function suppression(int $id): Response
    {
        // Récupération de la citation via son ID
        $citation = $this->citationRepository->find($id);

        // Retour sur la page d'index avec affichage d'un lessage si la citation n'existe pas
        if (!$citation) {
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_citations');
        }

        // Suppresion de la citation
        $this->citationRepository->remove($citation, true);

        // Retour sur la page d'index avec affichage d'un message de réussite
        $this->addFlash('success', 'La citation a été effacée.');
        return $this->redirectToRoute('app_citations');
    }
}
