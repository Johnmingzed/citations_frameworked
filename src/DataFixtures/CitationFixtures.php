<?php

namespace App\DataFixtures;

use App\Entity\Citation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Création de 50 citations factices et associations à l'un des auteurs présents en base de données
 */
class CitationFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * Charge les données des fixtures.
     *
     * @param ObjectManager $manager Gestionnaire d'entités Doctrine.
     */
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Création de 50 citations factices
        for ($cit = 1; $cit <= 50; $cit++) {
            $citation = new Citation();
            $citation->setCitation($faker->realText);

            // On récupère une référence à un auteur
            $auteur = $this->getReference('auteur-' . rand(1, 10));

            $citation->setAuteurId($auteur);

            $manager->persist($citation);
        }

        // Enregistrement des entités en base de données
        $manager->flush();
    }

    /**
     * Renvoie la liste des classes de fixtures auxquelles les fixtures actuelles sont dépendantes.
     *
     * @return array Tableau contenant les classes de fixtures dépendantes.
     */
    public function getDependencies(): array
    {
        return [
            AuteurFixtures::class
        ];
    }
}
