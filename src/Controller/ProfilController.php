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

class ProfilController extends AbstractController
{

    #[Route('/profil', name: 'app_profil')]
    public function index(UtilisateurRepository $utilisateurRepository,  Request $request): Response
    {

        $profil = $this->getUser();
        $utilisateur = $utilisateurRepository->find($profil->getId());

        if (!$utilisateur) {
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }

        $form = $this->createForm(UtilisateurFormType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Votre pofil a bien été modifié.';
            $message_type = 'alert-success';

            try {
                $utilisateurRepository->save($utilisateur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'alert-danger';
            }
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        return $this->render('profil/modifier.html.twig', [
            'controller_name' => 'ProfilController',
            'utilisateur' => $utilisateur,
            'utilisateurForm' => $form->createView()
        ]);
    }

    #[Route('/profil/changepw', name: 'app_profil_change_pw')]
    public function resetPW(utilisateurRepository $utilisateurRepository, Request $request): Response
    {

        $profil = $this->getUser();
        $utilisateur = $utilisateurRepository->find($profil->getId());

        if (!$utilisateur) {
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'message' => 'Action impossible : Référence inexistante.',
                'message_type' => 'alert-danger'
            ]);
        }

        $form = $this->createForm(UtilisateurFormType::class, $profil);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $message = 'Votre pofil a bien été modifié.';
            $message_type = 'alert-success';

            try {
                $utilisateurRepository->save($utilisateur, true);
            } catch (Exception $e) {
                $message = 'Action impossible : ' . $e->getMessage();
                $message_type = 'alert-danger';
            }
            // Retour sur la page d'index (⚠️ code redondant)
            return $this->render('main/index.html.twig', [
                'controller_name' => 'MainController',
                'message' => $message,
                'message_type' => $message_type
            ]);
        }

        return $this->render('profil/modifier.html.twig', [
            'controller_name' => 'ProfilController',
            'utilisateur' => $utilisateur,
            'utilisateurForm' => $form->createView(),
            'passwordchange' => true
        ]);
    }
}
