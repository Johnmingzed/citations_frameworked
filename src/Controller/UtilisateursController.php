<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\UtilisateurFormType;
use App\Repository\UtilisateurRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateursController extends AbstractController
{
    #[Route('/utilisateurs', name: 'app_utilisateurs')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('utilisateurs/index.html.twig', [
            'controller_name' => 'UtilisateursController',
            'utilisateurs' => $utilisateurRepository->findBy([], ['mail' => 'asc'])
        ]);
    }

    #[Route('/utilisateur/modifier/{id}', name: 'app_utilisateurs_modifier')]
    public function modification(UtilisateurRepository $utilisateurRepository, int $id, Request $request): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
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
                $utilisateurRepository->save($utilisateur, true);
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

    #[Route('/utilisateur/supprimer/{id}', name: 'app_utilisateurs_supprimer')]
    public function suppression(utilisateurRepository $utilisateurRepository, int $id): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        if (!$utilisateur) {
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_utilisateurs');
        }

        $utilisateur_mail = $utilisateur->getMail();
        $utilisateurRepository->remove($utilisateur, true);

        // Retour sur la page d'index
        $this->addFlash('success', $utilisateur_mail . ' a été effacé');
        return $this->redirectToRoute('app_utilisateurs');
    }

    #[Route('/utilisateur/resetpw/{id}', name: 'app_utilisateurs_reset_pw')]
    public function resetPW(utilisateurRepository $utilisateurRepository, int $id): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        if (!$utilisateur) {
            // Retour sur la page d'index
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_utilisateurs');
        }

        $utilisateur_mail = $utilisateur->getMail();
        $utilisateur->setPassword(null);
        $utilisateurRepository->save($utilisateur, true);

        // Retour sur la page d'index
        $this->addFlash('success', 'Le mot de passe associé à ' . $utilisateur_mail . ' a été réinitialisé.');
        return $this->redirectToRoute('app_utilisateurs');
    }
}
