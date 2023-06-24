<?php

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

class CitationsController extends AbstractController
{
    #[Route('/citations', name: 'app_citations')]
    public function index(CitationRepository $citationRepository, AuteurRepository $auteurRepository): Response
    {
        return $this->render('citations/index.html.twig', [
            'controller_name' => 'CitationsController',
            'auteurs' => $auteurRepository->findBy([]),
            'citations' => $citationRepository->findBy([], ['auteur_id' => 'asc'])
        ]);
    }

    #[Route('/citation/details/{id}', name: 'app_citations_detail')]
    public function detail(citationRepository $citationRepository, int $id): Response
    {
        if (!$citationRepository->find($id)) {
            return $this->render('citations/index.html.twig', [
                'controller_name' => 'CitationsController',
                'citations' => $citationRepository->findBy([]),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }
        return $this->render('citations/detail.html.twig', [
            'controller_name' => 'CitationsController',
            'citation' => $citationRepository->find($id)
        ]);
    }

    #[Route('/citation/modifier/{id}', name: 'app_citations_modifier')]
    public function modification(AuteurRepository $auteurRepository, CitationRepository $citationRepository, int $id, Request $request): Response
    {
        $citation = $citationRepository->find($id);
        if (!$citation) {
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('citations/index.html.twig', [
                'controller_name' => 'CitationsController',
                'auteurs' => $auteurRepository->findBy([]),
                'citations' => $citationRepository->findBy([], ['auteur_id' => 'asc']),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }
        $form = $this->createForm(CitationFormType::class, $citation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Citation modifiée avec succès.';
            $message_type = 'alert-success';

            try {
                $citationRepository->save($citation, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'alert-danger';
            }
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('citations/index.html.twig', [
                'controller_name' => 'CitationsController',
                'auteurs' => $auteurRepository->findBy([]),
                'citations' => $citationRepository->findBy([], ['auteur_id' => 'asc']),
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        return $this->render('citations/modifier.html.twig', [
            'controller_name' => 'CitationsController',
            'citation' => $citation,
            'citationForm' => $form->createView()
        ]);
    }

    #[Route('/citation/ajouter/', name: 'app_citations_ajouter')]
    public function ajouter(AuteurRepository $auteurRepository, citationRepository $citationRepository, Request $request): Response
    {
        $citation = new Citation();
        $form = $this->createForm(CitationFormType::class, $citation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Nouvelle citation créée avec succès.';
            $message_type = 'alert-success';

            try {
                $citationRepository->save($citation, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'alert-danger';
            }
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('citations/index.html.twig', [
                'controller_name' => 'CitationsController',
                'auteurs' => $auteurRepository->findBy([]),
                'citations' => $citationRepository->findBy([], ['auteur_id' => 'asc']),
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        return $this->render('citations/ajouter.html.twig', [
            'controller_name' => 'CitationsController',
            'citationForm' => $form->createView()
        ]);
    }

    #[Route('/citation/supprimer/{id}', name: 'app_citations_supprimer')]
    public function suppression(AuteurRepository $auteurRepository, citationRepository $citationRepository, int $id): Response
    {
        $citation = $citationRepository->find($id);
        if (!$citation) {
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('citations/index.html.twig', [
                'controller_name' => 'CitationsController',
                'citations' => $citationRepository->findBy([]),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }

        $citationRepository->remove($citation, true);

        // Retour sur la page d'index (⚠️ code redondant)
        return $this->render('citations/index.html.twig', [
            'controller_name' => 'citationsController',
            'auteurs' => $auteurRepository->findBy([]),
            'citations' => $citationRepository->findBy([], ['auteur_id' => 'asc']),
            'message' => 'La citation a été effacé',
            'message_type' => 'alert-success'
        ]);
    }
}
