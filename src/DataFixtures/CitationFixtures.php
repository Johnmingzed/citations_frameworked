<?php

namespace App\DataFixtures;

use App\Entity\Citation;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class CitationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = \Faker\Factory::create('fr_FR');

        for ($cit = 1; $cit <= 50; $cit++) {
            $citation = new Citation();
            $citation->setCitation($faker->text);

            // On va chercher une référence à un auteur
            $auteur = $this->getReference('auteur-' . rand(1, 10));

            $citation->setAuteurId($auteur);

            $manager->persist($citation);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            AuteurFixtures::class
        ];
    }
}
