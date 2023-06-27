<?php

namespace App\Controller;

use App\Form\UtilisateurFormType;
use App\Repository\UtilisateurRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Le contrôleur responsable de la gestion du profil de l'utilisateur.
 */
class ProfilController extends AbstractController
{
    /**
     * Affiche le formulaire de modification du profil de l'utilisateur.
     *
     * @param UtilisateurRepository $utilisateurRepository Le référentiel pour accéder aux utilisateurs.
     * @param Request               $request               La requête HTTP entrante.
     * 
     * @return Response La réponse HTTP contenant le formulaire de modification du profil ou la page d'index en cas d'erreur.
     * 
     */
    #[Route('/profil', name: 'app_profil')]
    public function udpate(UtilisateurRepository $utilisateurRepository,  Request $request): Response
    {
        // Récupérer le profil de l'utilisateur actuellement connecté
        $profil = $this->getUser();
        $utilisateur = $utilisateurRepository->find($profil->getId());

        if (!$utilisateur) {
            // Rediriger vers la page d'accueil avec un message d'erreur si le profil n'existe pas
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_main');
        }

        // Créer le formulaire de modification du profil
        $form = $this->createForm(UtilisateurFormType::class, $profil);
        $form->remove('admin');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Le formulaire a été soumis et les données sont valides
            $message = 'Votre pofil a bien été modifié.';
            $message_type = 'success';

            try {
                $utilisateurRepository->save($utilisateur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Rediriger vers la page d'accueil avec un message de succès ou d'erreur
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_main', [
                'saved' => $utilisateur
            ]);
        }

        // Afficher le formulaire de modification du profil
        return $this->render('profil/modifier.html.twig', [
            'controller_name' => 'ProfilController',
            'utilisateur' => $utilisateur,
            'utilisateurForm' => $form->createView()
        ]);
    }

    /**
     * Affiche le formulaire de modification du mot de passe de l'utilisateur.
     *
     * @param UtilisateurRepository $utilisateurRepository Le référentiel pour accéder aux utilisateurs.
     * @param Request               $request               La requête HTTP entrante.
     * 
     * @return Response La réponse HTTP contenant le formulaire de modification du mot de passe ou la page d'index en cas d'erreur.
     * 
     */
    #[Route('/profil/changepw', name: 'app_profil_change_pw')]
    public function resetPW(UtilisateurRepository $utilisateurRepository, Request $request): Response
    {

        // Récupérer le profil de l'utilisateur actuellement connecté
        $profil = $this->getUser();
        $utilisateur = $utilisateurRepository->find($profil->getId());

        if (!$utilisateur) {
            // Rediriger vers la page d'accueil avec un message d'erreur si le profil n'existe pas
            $this->addFlash('danger', 'Action impossible : Référence inexistante.');
            return $this->redirectToRoute('app_main');
        }

        // Créer le formulaire de modification du mot de passe
        $form = $this->createForm(UtilisateurFormType::class, $profil);
        $form->remove('admin');
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Le formulaire a été soumis et les données sont valides
            $message = 'Votre pofil a bien été modifié.';
            $message_type = 'success';

            try {
                $utilisateurRepository->save($utilisateur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'danger';
            }
            // Rediriger vers la page d'accueil avec un message de succès ou d'erreur
            $this->addFlash($message_type, $message);
            return $this->redirectToRoute('app_main');
        }

        // Afficher le formulaire de modification du mot de passe
        return $this->render('profil/modifier.html.twig', [
            'controller_name' => 'ProfilController',
            'utilisateur' => $utilisateur,
            'utilisateurForm' => $form->createView(),
            'passwordchange' => true
        ]);
    }
}
