<?php

namespace App\Controller;

use App\Repository\AuteurRepository;
use App\Repository\CitationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ApiController extends AbstractController
{
    #[Route('/api', name: 'api_citations_all', methods: ['GET'])]
    public function getAllCitations(CitationRepository $citationRepository, SerializerInterface $serializer): JsonResponse
    {
        $liste = $citationRepository->findAll();
        $jsonListe = $serializer->serialize($liste, 'json', ['groups' => 'getCitation']);
        return new JsonResponse($jsonListe, Response::HTTP_OK, [], true);
    }

    #[Route('/api/auteur/{nom}', name: 'api_auteur_details', methods: ['GET'])]
    public function getAuteurDetails(string $nom, AuteurRepository $auteurRepository, CitationRepository $citationRepository, SerializerInterface $serializer): JsonResponse
    {
        $auteur = $auteurRepository->findOneBy(['auteur' => $nom]);
        if ($auteur) {
            $jsonAuteur = $serializer->serialize($auteur, 'json', ['groups' => 'getAuteur']);
            return new JsonResponse($jsonAuteur, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }

    #[Route('/api/citation/{id}', name: 'api_citation_id', methods: ['GET'])]
    public function getDetailCitations(int $id, CitationRepository $citationRepository, SerializerInterface $serializer): JsonResponse
    {
        $citation = $citationRepository->find($id);
        if ($citation) {
            $jsonCitation = $serializer->serialize($citation, 'json',['groups' => 'getCitation']);
            return new JsonResponse($jsonCitation, Response::HTTP_OK, [], true);
        }
        return new JsonResponse(null, Response::HTTP_NOT_FOUND);
    }
}
