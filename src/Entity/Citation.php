<?php

namespace App\Entity;

use App\Entity\Trait\DateModifTrait;
use App\Repository\AuteurRepository;
use App\Repository\CitationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CitationRepository::class)]
#[ORM\Table(name: 'citations')]
class Citation
{
    use DateModifTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 511)]
    private ?string $citation = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $explication = null;

    #[ORM\ManyToOne(inversedBy: 'citations')]
    #[ORM\JoinColumn(onDelete: 'SET NULL')]
    private ?Auteur $auteur_id = null;

    public function __construct()
    {
        $this->date_modif = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCitation(): ?string
    {
        return $this->citation;
    }

    public function setCitation(string $citation): static
    {
        $this->citation = $citation;

        return $this;
    }

    public function getExplication(): ?string
    {
        return $this->explication;
    }

    public function setExplication(?string $explication): static
    {
        $this->explication = $explication;

        return $this;
    }

    public function getAuteurId(): ?Auteur
    {
        return $this->auteur_id;
    }

    public function setAuteurId(?Auteur $auteur_id): static
    {
        $this->auteur_id = $auteur_id;

        return $this;
    }
}
