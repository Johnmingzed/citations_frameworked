<?php

namespace App\Controller;

use App\Entity\Auteur;
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
    private $citations;

    public function __construct(CitationRepository $citationRepository)
    {
        return $this->citations = $citationRepository->createQueryBuilder('c')
            ->leftJoin('c.auteur_id', 'a')
            ->orderBy('a.auteur', 'ASC')
            ->getQuery()
            ->getResult();
    }

    #[Route('/citations', name: 'app_citations')]
    public function index(AuteurRepository $auteurRepository): Response
    {
        return $this->render('citations/index.html.twig', [
            'controller_name' => 'CitationsController',
            'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
            'citations' => $this->citations
        ]);
    }

    #[Route('/citation/details/{id}', name: 'app_citations_detail')]
    public function detail(citationRepository $citationRepository, int $id): Response
    {
        if (!$citationRepository->find($id)) {
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_citations');
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
        $auteurs = $auteurRepository->findBy([], ['auteur' => 'ASC']);
        if (!$citation) {
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_citations');
        }
        $form = $this->createForm(CitationFormType::class, $citation, ['auteur' => $auteurs]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Citation modifiée avec succès.';
            $message_type = 'success';

            try {
                $citationRepository->save($citation, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Retour sur la page d'index
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_citations');
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
        $auteurs = $auteurRepository->findBy([], ['auteur' => 'ASC']);
        $form = $this->createForm(CitationFormType::class, $citation, ['auteur' => $auteurs]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Nouvelle citation créée avec succès.';
            $message_type = 'success';

            try {
                $citationRepository->save($citation, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Retour sur la page d'index
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_citations');
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
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_citations');
        }

        $citationRepository->remove($citation, true);

        // Retour sur la page d'index
        $this->addFlash('success', 'La citation a été effacée.');
        return $this->redirectToRoute('app_citations');
    }
}
