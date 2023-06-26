<?php

namespace App\DataFixtures;

use App\Entity\Auteur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AuteurFixtures extends Fixture
{
    private $counter = 1;

    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($aut = 1; $aut <= 10; $aut++) {
            $auteur = new Auteur();
            $auteur->setAuteur($faker->name);
            $auteur->setBio($faker->realText);
            $manager->persist($auteur);

            $this->addReference('auteur-' . $this->counter, $auteur);
            $this->counter++;
        }

        $manager->flush();
    }
}
