<?php

/**
 * Nom du fichier : Citation.php
 * Description : Ce fichier contient la classe Citation qui représente une entité citation.
 */

namespace App\Entity;

use App\Entity\Trait\DateModifTrait;
use App\Repository\CitationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Citation
 * Cette classe représente une citation.
 */
#[ORM\Entity(repositoryClass: CitationRepository::class)]
#[ORM\Table(name: 'citations')]
class Citation
{
    use DateModifTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(["getCitation", "getAuteur"])]
    private ?int $id = null;

    #[ORM\Column(length: 511)]
    #[Groups(["getCitation", "getAuteur"])]
    private ?string $citation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["getCitation", "getAuteur"])]
    private ?string $explication = null;

    #[ORM\ManyToOne(inversedBy: 'citations')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    #[Groups(["getCitation"])]
    private ?Auteur $auteur_id = null;

    public function __construct()
    {
        $this->date_modif = new \DateTime();
    }

    /**
     * Obtient l'identifiant de la citation.
     *
     * @return int|null L'identifiant de la citation
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtient la citation.
     *
     * @return string|null La citation
     */
    public function getCitation(): ?string
    {
        return html_entity_decode($this->citation);
    }

    /**
     * Définit la citation.
     *
     * @param string $citation La citation
     * @return static L'instance de la citation
     */
    public function setCitation(string $citation): static
    {
        $this->citation = $citation;

        return $this;
    }

    /**
     * Obtient l'explication de la citation.
     *
     * @return string|null L'explication de la citation
     */
    public function getExplication(): ?string
    {
        return html_entity_decode($this->explication);
    }

    /**
     * Définit l'explication de la citation.
     *
     * @param string|null $explication L'explication de la citation
     * @return static L'instance de la citation
     */
    public function setExplication(?string $explication): static
    {
        $this->explication = $explication;

        return $this;
    }

    /**
     * Obtient l'auteur de la citation.
     *
     * @return Auteur|null L'auteur de la citation
     */
    public function getAuteurId(): ?Auteur
    {
        return $this->auteur_id;
    }

    /**
     * Définit l'auteur de la citation.
     *
     * @param Auteur|null $auteur_id L'auteur de la citation
     * @return static L'instance de la citation
     */
    public function setAuteurId(?Auteur $auteur_id): static
    {
        $this->auteur_id = $auteur_id;

        return $this;
    }
}
