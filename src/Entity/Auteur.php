<?php

namespace App\Entity;

use App\Entity\Trait\DateModifTrait;
use App\Repository\AuteurRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

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
    private ?string $auteur = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $bio = null;

    #[ORM\OneToMany(mappedBy: 'auteur_id', targetEntity: Citation::class)]
    private Collection $citations;

    public function __construct()
    {
        $this->citations = new ArrayCollection();
        $this->date_modif = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuteur(): ?string
    {
        return html_entity_decode($this->auteur);
    }

    public function setAuteur(string $auteur): static
    {
        $this->auteur = $auteur;

        return $this;
    }

    public function getBio(): ?string
    {
        return html_entity_decode($this->bio);
    }

    public function setBio(?string $bio): static
    {
        $this->bio = $bio;

        return $this;
    }

    /**
     * @return Collection<int, Citation>
     */
    public function getCitations(): Collection
    {
        return $this->citations;
    }

    public function addCitation(Citation $citation): static
    {
        if (!$this->citations->contains($citation)) {
            $this->citations->add($citation);
            $citation->setAuteurId($this);
        }

        return $this;
    }

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
