<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Création de 6 comptes utilisateurs (dont 1 compte admin) en base de données
 */
class UtilisateurFixtures extends Fixture
{
    /**
     * Constructeur de la classe UtilisateurFixtures.
     *
     * @param UserPasswordHasherInterface $passwordEncoder Service de hachage des mots de passe des utilisateurs.
     */
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
    }

    /**
     * Charge les données des fixtures.
     *
     * @param ObjectManager $manager Gestionnaire d'entités Doctrine.
     */
    public function load(ObjectManager $manager): void
    {
        // Création d'un administrateur
        $admin = new Utilisateur();
        $admin->setMail('jonathan.pchs@gmail.com');
        $admin->setPrenom('Jonathan');
        $admin->setNom('PAIN-CHAMMING\'S');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, '1234testadmin')
        );
        $admin->setAdmin(true);

        $manager->persist($admin);

        // Création d'un utilisateur non administrateur
        $notadmin = new Utilisateur();
        $notadmin->setMail('notanadmin@gmail.com');
        $notadmin->setPrenom('John');
        $notadmin->setNom('DOE');
        $notadmin->setPassword(
            $this->passwordEncoder->hashPassword($notadmin, '1234test')
        );
        $notadmin->setAdmin(0);

        $manager->persist($notadmin);

        $faker = \Faker\Factory::create('fr_FR');

        // Création de 4 utilisateurs factices
        for ($usr = 1; $usr <= 4; $usr++) {
            $user = new Utilisateur();
            $user->setMail($faker->email);
            $user->setPrenom($faker->firstName);
            $user->setNom($faker->lastName);
            $user->setPassword(
                $this->passwordEncoder->hashPassword($user, '123456789')
            );

            $manager->persist($user);
        }

        // Enregistrement des entités en base de données
        $manager->flush();
    }
}