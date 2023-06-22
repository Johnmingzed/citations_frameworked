<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UtilisateurFixtures extends Fixture
{
    public function __construct(private UserPasswordHasherInterface $passwordEncoder)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $admin = new Utilisateur();
        $admin->setMail('jonathan.pchs@gmail.com');
        $admin->setPrenom('Jonathan');
        $admin->setNom('PAIN-CHAMMING\'S');
        $admin->setPassword(
            $this->passwordEncoder->hashPassword($admin, '1234testadmin')
        );
        $admin->setAdmin(true);

        $manager->persist($admin);

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
        $manager->flush();
    }
}
