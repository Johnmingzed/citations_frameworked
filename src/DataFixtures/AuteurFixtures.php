<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Création de 10 auteurs factices en base de données
 */
class AuteurFixtures extends Fixture
{
    private $counter = 1;

    /**
     * Charge les données des fixtures.
     *
     * @param ObjectManager $manager Gestionnaire d'entités Doctrine.
     */
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        // Création de 10 auteurs factices
        for ($aut = 1; $aut <= 10; $aut++) {
            $auteur = new Auteur();
            $auteur->setAuteur($faker->name);
            $auteur->setBio($faker->realText(rand(300,600)));
            $manager->persist($auteur);

            // Ajout d'une référence pour chaque auteur
            $this->addReference('auteur-' . $this->counter, $auteur);
            $this->counter++;
        }

        // Enregistrement des entités en base de données
        $manager->flush();
    }
}
