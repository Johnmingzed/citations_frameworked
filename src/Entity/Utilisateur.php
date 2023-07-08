<?php

/**
 * Nom du fichier : Utilisateur.php
 * Description : Ce fichier contient la classe Utilisateur qui représente une entité utilisateur.
 */

namespace App\Entity;

use App\Entity\Trait\DateModifTrait;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Class Utilisateur
 * Cette classe représente un utilisateur.
 */
#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[ORM\Table(name: 'utilisateurs')]
#[UniqueEntity(fields: ['mail'], message: 'Il existe déjà un utilisateur lié à cette adresse mail.')]
class Utilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    use DateModifTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 320, unique: true)]
    private ?string $mail = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column(name: 'mot_de_passe', nullable: true)]
    private ?string $password = null;

    #[ORM\Column(length: 31, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(length: 31, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 127, nullable: true)]
    private ?string $token = null;

    #[ORM\Column]
    private ?bool $admin = false;

    public function __construct()
    {
        $this->date_modif = new \DateTime();
    }

    /**
     * Obtient l'identifiant de l'utilisateur.
     *
     * @return int|null L'identifiant de l'utilisateur
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Obtient l'adresse e-mail de l'utilisateur.
     *
     * @return string|null L'adresse e-mail de l'utilisateur
     */
    public function getMail(): ?string
    {
        return $this->mail;
    }

    /**
     * Définit l'adresse e-mail de l'utilisateur.
     *
     * @param string $mail L'adresse e-mail de l'utilisateur
     * @return static L'instance de l'utilisateur
     */
    public function setMail(string $mail): static
    {
        $this->mail = $mail;

        return $this;
    }

    /**
     * Obtient l'identifiant de l'utilisateur (utilisé pour l'authentification).
     *
     * @return string L'identifiant de l'utilisateur
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    /**
     * Obtient les rôles de l'utilisateur.
     *
     * @return array Les rôles de l'utilisateur
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_TESTER';

        return array_unique($roles);
    }

    /**
     * Définit les rôles de l'utilisateur.
     *
     * @param array $roles Les rôles de l'utilisateur
     * @return static L'instance de l'utilisateur
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Obtient le mot de passe haché de l'utilisateur.
     *
     * @return string|null Le mot de passe haché de l'utilisateur
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Définit le mot de passe haché de l'utilisateur.
     *
     * @param string|null $password Le mot de passe haché de l'utilisateur
     * @return static L'instance de l'utilisateur
     */
    public function setPassword(?string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Efface les informations sensibles de l'utilisateur.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * Obtient le prénom de l'utilisateur.
     *
     * @return string|null Le prénom de l'utilisateur
     */
    public function getPrenom(): ?string
    {
        return html_entity_decode($this->prenom);
    }

    /**
     * Définit le prénom de l'utilisateur.
     *
     * @param string|null $prenom Le prénom de l'utilisateur
     * @return static L'instance de l'utilisateur
     */
    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    /**
     * Définit le prénom de l'utilisateur.
     *
     * @param string|null $prenom Le prénom de l'utilisateur
     * @return static L'instance de l'utilisateur
     */
    public function getNom(): ?string
    {
        return html_entity_decode($this->nom);
    }

    /**
     * Définit le nom de famille de l'utilisateur.
     *
     * @param string|null $nom Le nom de famille de l'utilisateur
     * @return static L'instance de l'utilisateur
     */
    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Obtient le token de l'utilisateur.
     *
     * @return string|null Le token de l'utilisateur
     */
    public function getToken(): ?string
    {
        return $this->token;
    }

    /**
     * Définit le token de l'utilisateur.
     *
     * @param string|null $token Le token de l'utilisateur
     * @return static L'instance de l'utilisateur
     */
    public function setToken(?string $token): static
    {
        $this->token = $token;

        return $this;
    }

    /**
     * Indique si l'utilisateur est administrateur.
     *
     * @return bool|null Vrai si l'utilisateur est administrateur, sinon faux
     */
    public function isAdmin(): ?bool
    {
        return $this->admin;
    }

    /**
     * Définit si l'utilisateur est administrateur.
     * Si l'utilisateur est un administrateur, le rôle ROLE_ADMIN sera ajouté à ses rôles.
     * Sinon, le rôle ROLE_USER sera ajouté à ses rôles.
     *
     * @param bool $admin Vrai si l'utilisateur est administrateur, sinon faux
     * @return static L'instance de l'utilisateur
     */
    public function setAdmin(bool $admin): static
    {
        if ($admin == true) {
            $roles[] = 'ROLE_ADMIN';
        } else {
            $roles[] = 'ROLE_USER';
        }
        $this->setRoles($roles);
        $this->admin = $admin;

        return $this;
    }
}
