<?php

/**
 * Nom du fichier : DateModifTrait.php
 * Description : Ce fichier contient le trait DateModifTrait pour gérer la date de modification d'une entité.
 */

namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

/**
 * Trait DateModifTrait
 * Ce trait ajoute une propriété "date_modif" et les méthodes associées pour gérer la date de modification d'une entité.
 */
trait DateModifTrait
{
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date_modif = null;

    /**
     * Getter pour la propriété "date_modif"
     *
     * @return \DateTimeInterface|null La date de modification de l'entité
     */
    public function getDateModif(): ?\DateTimeInterface
    {
        return $this->date_modif;
    }

    /**
     * Setter pour la propriété "date_modif"
     *
     * @param \DateTimeInterface $date_modif La date de modification de l'entité
     * @return static L'instance de l'entité
     */
    public function setDateModif(\DateTimeInterface $date_modif): static
    {
        $this->date_modif = $date_modif;

        return $this;
    }
}
