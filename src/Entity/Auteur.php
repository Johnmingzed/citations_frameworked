<?php

/**
 * Nom du fichier : Auteurs.php
 * Description : Ce fichier contient la classe Auteur qui représente une entité auteur.
 */

namespace App\Entity;

use App\Entity\Trait\DateModifTrait;
use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Class Auteur
 * Cette classe représente un auteur.
 */
#[ORM\Entity(repositoryClass: AuteurRepository::class)]
#[ORM\Table(name: 'auteurs')]
class Auteur
{
    use DateModifTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 63, unique: true)]
    #[Groups(["getAuteur", "getCitation"])]
    private ?string $auteur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(["getAuteur", "getCitation"])]
    private ?string $bio = null;

    #[ORM\OneToMany(mappedBy: 'auteur_id', targetEntity: Citation::class)]
    #[Groups(["getAuteur"])]
    private Collection $citations;

    public function __construct()
    {
        $this->date_modif = new \DateTime();
    }

    /**
     * Obtient l'identifiant de l'auteur.
     *
     * @return int|null L'identifiant de l'auteur
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtient le nom de l'auteur.
     *
     * @return string|null Le nom de l'auteur
     */
    public function getAuteur(): ?string
    {
        return html_entity_decode($this->auteur);
    }

    /**
     * Définit le nom de l'auteur.
     *
     * @param string $auteur Le nom de l'auteur
     * @return static L'instance de l'auteur
     */
    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    /**
     * Obtient la biographie de l'auteur.
     *
     * @return string|null La biographie de l'auteur
     */
    public function getBio(): ?string
    {
        return html_entity_decode($this->bio);
    }

    /**
     * Définit la biographie de l'auteur.
     *
     * @param string|null $bio La biographie de l'auteur
     * @return static L'instance de l'auteur
     */
    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * Obtient la collection de citations associées à l'auteur.
     *
     * @return Collection<int, Citation> La collection de citations
     */
    public function getCitations(): Collection
    {
        return $this->citations;
    }

    /**
     * Ajoute une citation à l'auteur.
     *
     * @param Citation $citation La citation à ajouter
     * @return static L'instance de l'auteur
     */
    public function addCitation(Citation $citation): static
    {
        if (!$this->citations->contains($citation)) {
            $this->citations->add($citation);
            $citation->setAuteurId($this);
        }

        return $this;
    }

    /**
     * Supprime une citation de l'auteur.
     *
     * @param Citation $citation La citation à supprimer
     * @return static L'instance de l'auteur
     */
    public function removeCitation(Citation $citation): static
    {
        if ($this->citations->removeElement($citation)) {
            // set the owning side to null (unless already changed)
            if ($citation->getAuteurId() === $this) {
                $citation->setAuteurId(null);
            }
        }

        return $this;
    }
}
