<?php

/**
 * Nom du fichier : ApiController.php
 * Description : Ce fichier contient la classe ApiController qui gère les fonctionnalités de l'API.
 */

namespace App\Controller;

use App\Entity\Citation;
use App\Repository\AuteurRepository;
use App\Repository\CitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Contrôleur ApiController
 * Gère les fonctionnalités CRUD de l'API pour les citations.
 */
class ApiController extends AbstractController
{
    /**
     * Récupère toutes les citations
     * Renvoie une liste de toutes les citations au format JSON.
     * 
     * @param CitationRepository $citationRepository Le repository des citations.
     * @param SerializerInterface $serializer Le service de sérialisation.
     * @return JsonResponse
     */
    #[Route('/api', name: 'api_citations_all', methods: ['GET'])]
    public function getAllCitations(CitationRepository $citationRepository, SerializerInterface $serializer): JsonResponse
    {
        $liste = $citationRepository->findAll();
        $jsonListe = $serializer->serialize($liste, 'json', ['groups' => 'getCitation']);
        return new JsonResponse($jsonListe, Response::HTTP_OK, [], true);
    }

    /**
     * Récupère les détails d'un auteur
     * Renvoie les détails d'un auteur spécifié au format JSON.
     * 
     * @param string $nom Le nom de l'auteur.
     * @param AuteurRepository $auteurRepository Le repository des auteurs.
     * @param SerializerInterface $serializer Le service de sérialisation.
     * @return JsonResponse
     */
    #[Route('/api/auteur/{nom}', name: 'api_auteur_details', methods: ['GET'])]
    public function getAuteurDetails(string $nom, AuteurRepository $auteurRepository, SerializerInterface $serializer): JsonResponse
    {
        $auteur = $auteurRepository->findOneBy(['auteur' => $nom]);
        if ($auteur) {
            $jsonAuteur = $serializer->serialize($auteur, 'json', ['groups' => 'getAuteur']);
            return new JsonResponse($jsonAuteur, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Récupère les détails d'une citation
     * Renvoie les détails d'une citation spécifiée au format JSON.
     * 
     * @param int $id L'identifiant de la citation.
     * @param CitationRepository $citationRepository Le repository des citations.
     * @param SerializerInterface $serializer Le service de sérialisation.
     * @return JsonResponse
     */
    #[Route('/api/citation/{id}', name: 'api_citation_details', methods: ['GET'])]
    public function getDetailCitation(int $id, CitationRepository $citationRepository, SerializerInterface $serializer): JsonResponse
    {
        $citation = $citationRepository->find($id);
        if ($citation) {
            $jsonCitation = $serializer->serialize($citation, 'json', ['groups' => 'getCitation']);
            return new JsonResponse($jsonCitation, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Supprime une citation
     * Supprime une citation spécifiée.
     * 
     * @param int $id L'identifiant de la citation.
     * @param CitationRepository $citationRepository Le repository des citations.
     * @return JsonResponse
     */
    #[Route('/api/citation/{id}', name: 'api_citation_delete', methods: ['DELETE'])]
    public function deleteCitation(int $id, CitationRepository $citationRepository): JsonResponse
    {
        $citation = $citationRepository->find($id);
        $citationRepository->remove($citation, true);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Crée une nouvelle citation
     * Crée une nouvelle citation à partir des données fournies.
     * 
     * @param UrlGeneratorInterface $url L'interface UrlGenerator.
     * @param AuteurRepository $auteurRepository Le repository des auteurs.
     * @param CitationRepository $citationRepository Le repository des citations.
     * @param SerializerInterface $serializer Le service de sérialisation.
     * @param Request $request La requête HTTP.
     * @return JsonResponse
     */
    #[Route('/api/citation', name: 'api_citations_create', methods: ['POST'])]
    public function createCitation(UrlGeneratorInterface $url, AuteurRepository $auteurRepository, CitationRepository $citationRepository, SerializerInterface $serializer, Request $request): JsonResponse
    {
        $citation = $serializer->deserialize($request->getContent(), Citation::class, 'json');
        $content = $request->toArray();
        $auteurName = $content['auteur'] ?? null;
        $citation->setAuteurId($auteurRepository->findOneBy(['auteur' => $auteurName]));
        $citationRepository->save($citation, true);
        $jsonCitation = $serializer->serialize($citation, 'json', ['groups' => 'getCitation']);
        $location = $url->generate('api_citation_details', ['id' => $citation->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCitation, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    /**
     * Met à jour une citation
     * Met à jour une citation spécifiée avec les nouvelles données fournies.
     * 
     * @param int $id L'identifiant de la citation.
     * @param Request $request La requête HTTP.
     * @param AuteurRepository $auteurRepository Le repository des auteurs.
     * @param CitationRepository $citationRepository Le repository des citations.
     * @param SerializerInterface $serializer Le service de sérialisation.
     * @return JsonResponse
     */
    #[Route('/api/citation/{id}', name: 'api_citation_update', methods: ['PUT'])]
    public function updateCitation(int $id, Request $request, AuteurRepository $auteurRepository, CitationRepository $citationRepository, SerializerInterface $serializer): JsonResponse
    {
        $currentCitation = $citationRepository->find($id);
        $updateCitation = $serializer->deserialize(
            $request->getContent(),
            Citation::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentCitation]
        );
        $content = $request->toArray();
        $auteurName = $content['auteur'] ?? null;
        $updateCitation->setAuteurId($auteurRepository->findOneBy(['auteur' => $auteurName]));
        $citationRepository->save($updateCitation, true);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
