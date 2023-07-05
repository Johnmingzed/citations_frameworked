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
    private $citationRepository;
    private $serializer;
    private $auteurRepository;

    /**
     * Initialisation de la classe avec injection des dépendances
     * 
     * @param AuteurRepository $auteurRepository Le repository des auteurs.
     * @param CitationRepository $citationRepository Le repository des citations.
     * @param SerializerInterface $serializer Le service de sérialisation.
     */
    public function __construct(AuteurRepository $auteurRepository, CitationRepository $citationRepository, SerializerInterface $serializer)
    {
        $this->citationRepository = $citationRepository;
        $this->serializer = $serializer;
        $this->auteurRepository = $auteurRepository;
    }
    /**
     * Récupère toutes les citations
     * Renvoie une liste de toutes les citations au format JSON.
     * 
     * @return JsonResponse
     */
    #[Route('/api', name: 'api_citations_all', methods: ['GET'])]
    public function getAllCitations(): JsonResponse
    {
        $liste = $this->citationRepository->findAll();
        $jsonListe = $this->serializer->serialize($liste, 'json', ['groups' => 'getCitation']);
        return new JsonResponse($jsonListe, Response::HTTP_OK, [], true);
    }

    /**
     * Récupère les détails d'un auteur
     * Renvoie les détails d'un auteur spécifié au format JSON.
     * 
     * @param string $nom Le nom de l'auteur.
     * @return JsonResponse
     */
    #[Route('/api/auteur/{nom}', name: 'api_auteur_details', methods: ['GET'])]
    public function getAuteurDetails(string $nom): JsonResponse
    {
        $auteur = $this->auteurRepository->findOneBy(['auteur' => $nom]);
        if ($auteur) {
            $jsonAuteur = $this->serializer->serialize($auteur, 'json', ['groups' => 'getAuteur']);
            return new JsonResponse($jsonAuteur, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Récupère les détails d'une citation
     * Renvoie les détails d'une citation spécifiée au format JSON.
     * 
     * @param int $id L'identifiant de la citation.
     * @return JsonResponse
     */
    #[Route('/api/citation/{id}', name: 'api_citation_details', methods: ['GET'])]
    public function getDetailCitation(int $id): JsonResponse
    {
        $citation = $this->citationRepository->find($id);
        if ($citation) {
            $jsonCitation = $this->serializer->serialize($citation, 'json', ['groups' => 'getCitation']);
            return new JsonResponse($jsonCitation, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    /**
     * Supprime une citation
     * Supprime une citation spécifiée.
     * 
     * @param int $id L'identifiant de la citation.
     * @return JsonResponse
     */
    #[Route('/api/citation/{id}', name: 'api_citation_delete', methods: ['DELETE'])]
    public function deleteCitation(int $id): JsonResponse
    {
        $citation = $this->citationRepository->find($id);
        $this->citationRepository->remove($citation, true);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }

    /**
     * Crée une nouvelle citation
     * Crée une nouvelle citation à partir des données fournies.
     * 
     * @param UrlGeneratorInterface $url L'interface UrlGenerator.
     * @param Request $request La requête HTTP.
     * @return JsonResponse
     */
    #[Route('/api/citation', name: 'api_citations_create', methods: ['POST'])]
    public function createCitation(UrlGeneratorInterface $url, Request $request): JsonResponse
    {
        $citation = $this->serializer->deserialize($request->getContent(), Citation::class, 'json');
        $content = $request->toArray();
        $auteurName = $content['auteur'] ?? null;
        $citation->setAuteurId($this->auteurRepository->findOneBy(['auteur' => $auteurName]));
        $this->citationRepository->save($citation, true);
        $jsonCitation = $this->serializer->serialize($citation, 'json', ['groups' => 'getCitation']);
        $location = $url->generate('api_citation_details', ['id' => $citation->getId()], UrlGeneratorInterface::ABSOLUTE_URL);

        return new JsonResponse($jsonCitation, Response::HTTP_CREATED, ["Location" => $location], true);
    }

    /**
     * Met à jour une citation
     * Met à jour une citation spécifiée avec les nouvelles données fournies.
     * 
     * @param int $id L'identifiant de la citation.
     * @param Request $request La requête HTTP.
     * @return JsonResponse
     */
    #[Route('/api/citation/{id}', name: 'api_citation_update', methods: ['PUT'])]
    public function updateCitation(int $id, Request $request): JsonResponse
    {
        $currentCitation = $this->citationRepository->find($id);
        $updateCitation = $this->serializer->deserialize(
            $request->getContent(),
            Citation::class,
            'json',
            [AbstractNormalizer::OBJECT_TO_POPULATE => $currentCitation]
        );
        $content = $request->toArray();
        $auteurName = $content['auteur'] ?? null;
        $updateCitation->setAuteurId($this->auteurRepository->findOneBy(['auteur' => $auteurName]));
        $this->citationRepository->save($updateCitation, true);

        return new JsonResponse(null, Response::HTTP_NO_CONTENT);
    }
}
