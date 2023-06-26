<?php

namespace App\Controller;

use App\Entity\Utilisateur;
// use App\Form\UtilisateurFormType;
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

/*     #[Route('/utilisateur/details/{id}', name: 'app_utilisateurs_detail')]
    public function detail(utilisateurRepository $utilisateurRepository, int $id): Response
    {
        if (!$utilisateurRepository->find($id)) {
            return $this->render('utilisateurs/index.html.twig', [
                'controller_name' => 'utilisateursController',
                'utilisateurs' => $utilisateurRepository->findBy([]),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }
        return $this->render('utilisateurs/detail.html.twig', [
            'controller_name' => 'utilisateursController',
            'utilisateur' => $utilisateurRepository->find($id)
        ]);
    } */

/*     #[Route('/utilisateur/modifier/{id}', name: 'app_utilisateurs_modifier')]
    public function modification(utilisateurRepository $utilisateurRepository, int $id, Request $request): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        if (!$utilisateur) {
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('utilisateurs/index.html.twig', [
                'controller_name' => 'utilisateursController',
                'utilisateurs' => $utilisateurRepository->findBy([], ['utilisateur' => 'asc']),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }
        $form = $this->createForm(utilisateurFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'utilisateur modifié avec succès.';
            $message_type = 'alert-success';

            try {
                $utilisateurRepository->save($utilisateur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'alert-danger';
            }
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('utilisateurs/index.html.twig', [
                'controller_name' => 'utilisateursController',
                'utilisateurs' => $utilisateurRepository->findBy([], ['utilisateur' => 'asc']),
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        return $this->render('utilisateurs/modifier.html.twig', [
            'controller_name' => 'utilisateursController',
            'utilisateur' => $utilisateur,
            'utilisateurForm' => $form->createView()
        ]);
    } */

 /*    #[Route('/utilisateur/ajouter/', name: 'app_utilisateurs_ajouter')]
    public function ajouter(utilisateurRepository $utilisateurRepository, Request $request): Response
    {
        $utilisateur = new utilisateur();
        $form = $this->createForm(utilisateurFormType::class, $utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Nouvel utilisateur créé avec succès.';
            $message_type = 'alert-success';

            try {
                $utilisateurRepository->save($utilisateur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'alert-danger';
            }
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('utilisateurs/index.html.twig', [
                'controller_name' => 'utilisateursController',
                'utilisateurs' => $utilisateurRepository->findBy([], ['utilisateur' => 'asc']),
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        return $this->render('utilisateurs/ajouter.html.twig', [
            'controller_name' => 'utilisateursController',
            'utilisateurForm' => $form->createView()
        ]);
    } */

/*     #[Route('/utilisateur/supprimer/{id}', name: 'app_utilisateurs_supprimer')]
    public function suppression(utilisateurRepository $utilisateurRepository, int $id): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        if (!$utilisateur) {
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('utilisateurs/index.html.twig', [
                'controller_name' => 'utilisateursController',
                'utilisateurs' => $utilisateurRepository->findBy([]),
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }

        $utilisateur_name = $utilisateur->getutilisateur();
        $utilisateurRepository->remove($utilisateur, true);

        // Retour sur la page d'index (⚠️ code redondant)
        return $this->render('utilisateurs/index.html.twig', [
            'controller_name' => 'utilisateursController',
            'utilisateurs' => $utilisateurRepository->findBy([]),
            'message' => $utilisateur_name . ' a été effacé',
            'message_type' => 'alert-success'
        ]);
    } */
}
