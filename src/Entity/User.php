<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * User
 *
 * @ORM\Table(name="user", uniqueConstraints={@ORM\UniqueConstraint(name="login", columns={"login"}), @ORM\UniqueConstraint(name="email", columns={"email"})})
 * @ORM\Entity
 * @UniqueEntity(fields={"email"}, message="There is already an account with this email")
 */
class User implements UserInterface
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("user")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Nom est requis")
     * @Groups("user")
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Prénom est requis")
     * @Groups("user")
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="date_naissance", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="DateNaissance est requis")
     * @Groups("user")
     */
    private $dateNaissance;

    /**
     * @var string
     *
     * @ORM\Column(name="sexe", type="string", length=255, nullable=false)
     * @Groups("user")
     */
    private $sexe;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="E-mail est requis")
     * @Assert\Email(message="Cet E-mail n'est pas valide")
     * @Groups("user")
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=255, nullable=false)
     * @Groups("user")
     */
    private $role;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255, nullable=false)
     * @Groups("user")
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255, nullable=false)
     * @Groups("user")
     */
    private $password;

    /**
     * @var string
     *
     * @ORM\Column(name="status", type="string", length=255, nullable=false)
     * @Groups("user")
     */
    private $status;

    /**
     * @var string|null
     *
     * @ORM\Column(name="photo_profil", type="string", length=255, nullable=true)
     * @Groups("user")
     */
    private $photoProfil;

    /**
     * @var string|null
     *
     * @ORM\Column(name="biography", type="string", length=255, nullable=true)
     * @Groups("user")
     */
    private $biography;

    /**
     * @var string|null
     *
     * @ORM\Column(name="curriculum_vitae", type="string", length=255, nullable=true)
     * @Groups("user")
     */
    private $curriculumVitae;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idFormation = new \Doctrine\Common\Collections\ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function getDateNaissance(): ?string
    {
        return $this->dateNaissance;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function getPhotoProfil(): ?string
    {
        return $this->photoProfil;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function getCurriculumVitae(): ?string
    {
        return $this->curriculumVitae;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function setPrenom(string $x): self
    {
        $this->prenom = $x;

        return $this;
    }

    public function setDateNaissance(string $date): self
    {
        $this->dateNaissance = $date;

        return $this;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function setEmail(string $mail): self
    {
        $this->email = $mail;

        return $this;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;

        return $this;
    }

    public function setLogin(string $log): self
    {
        $this->login = $log;

        return $this;
    }

    public function setPassword(string $pass): self
    {
        $this->password = $pass;

        return $this;
    }

    public function setstatus(string $stat): self
    {
        $this->status = $stat;

        return $this;
    }

    public function setPhotoProfil(string $lien): self
    {
        $this->photoProfil = $lien;

        return $this;
    }

    public function setBiography(string $bio): self
    {
        $this->biography = $bio;

        return $this;
    }

    public function setCurriculumVitae(string $cv): self
    {
        $this->curriculumVitae = $cv;

        return $this;
    }

    public function getSalt()
    {
    }

    public function getUsername()
    {
        return $this->email;
    }

    public function getRoles()
    {
        return array('ROLE_USER');
    }

    public function eraseCredentials()
    {
    }


    public function __toString() 
{
    return (string) $this->id; 
}


}
