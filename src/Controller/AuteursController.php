<?php

namespace App\Controller;

use App\Entity\Auteur;
use App\Form\AuteurFormType;
use App\Repository\AuteurRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
        if (!$auteurRepository->find($id)) {
            return $this->render('auteurs/index.html.twig', [
                'controller_name' => 'AuteursController',
                'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }
        return $this->render('auteurs/detail.html.twig', [
            'controller_name' => 'AuteursController',
            'auteur' => $auteurRepository->find($id)
        ]);
    }

    #[Route('/auteur/modifier/{id}', name: 'app_auteurs_modifier')]
    public function modification(AuteurRepository $auteurRepository, int $id, Request $request): Response
    {
        $auteur = $auteurRepository->find($id);
        if (!$auteur) {
            return $this->render('auteurs/index.html.twig', [
                'controller_name' => 'AuteursController',
                'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }
        $form = $this->createForm(AuteurFormType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Auteur modifié avec succès.';
            $message_type = 'alert-success';

            try {
                $auteurRepository->save($auteur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'alert-danger';
            }
            return $this->render('auteurs/index.html.twig', [
                'controller_name' => 'AuteursController',
                'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        return $this->render('auteurs/modifier.html.twig', [
            'controller_name' => 'AuteursController',
            'auteur' => $auteur,
            'auteurForm' => $form->createView()
        ]);
    }

    #[Route('/auteur/ajouter/', name: 'app_auteurs_ajouter')]
    public function ajouter(AuteurRepository $auteurRepository, Request $request): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurFormType::class, $auteur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Nouvel auteur créé avec succès.';
            $message_type = 'alert-success';

            try {
                $auteurRepository->save($auteur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'alert-danger';
            }
            return $this->render('auteurs/index.html.twig', [
                'controller_name' => 'AuteursController',
                'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        return $this->render('auteurs/ajouter.html.twig', [
            'controller_name' => 'AuteursController',
            'auteurForm' => $form->createView()
        ]);
    }

    #[Route('/auteur/supprimer/{id}', name: 'app_auteurs_supprimer')]
    public function suppression(AuteurRepository $auteurRepository, int $id): Response
    {
        $auteur = $auteurRepository->find($id);
        if (!$auteur) {
            return $this->render('auteurs/index.html.twig', [
                'controller_name' => 'AuteursController',
                'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }
        $auteur_name = $auteur->getAuteur();
        $auteurRepository->remove($auteur, true);
        return $this->render('auteurs/index.html.twig', [
            'controller_name' => 'AuteursController',
            'auteurs' => $auteurRepository->findBy([], ['auteur' => 'asc']),
            'message' => $auteur_name . ' a été effacé',
            'message_type' => 'alert-success'
        ]);
    }
}
