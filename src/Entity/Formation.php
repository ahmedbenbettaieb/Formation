<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Formation
 *
 * @ORM\Table(name="formation", indexes={@ORM\Index(name="fk_idformateur", columns={"id_formateur"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\FormationRepository")
 */
class Formation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="titre is required")
     */
    private $titre;


    /**
     * @var int
     *
     * @ORM\Column(name="note", type="integer", nullable=false)
     */
    private $note;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", length=65535, nullable=false)
     * @Assert\NotBlank(message="description is required")
     */
    private $description;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_formateur", referencedColumnName="id")
     * })
     */
    private $idFormateur;

    /**
     * @var \Doctrine\Common\Collections\Collection
     *
     * @ORM\ManyToMany(targetEntity="User", inversedBy="idFormation")
     * @ORM\JoinTable(name="abonnement",
     *   joinColumns={
     *     @ORM\JoinColumn(name="id_formation", referencedColumnName="id")
     *   },
     *   inverseJoinColumns={
     *     @ORM\JoinColumn(name="id_etudiant", referencedColumnName="id")
     *   }
     * )
     */
    private $idEtudiant;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->idEtudiant = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId(?int $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getTitre(): ?string
    {
        return $this->titre;
    }

    /**
     * @param string $titre
     */
    public function setTitre(?string $titre): void
    {
        $this->titre = $titre;
    }

    /**
     * @return int
     */
    public function getNote(): ?int
    {
        return $this->note;
    }

    /**
     * @param int $note
     */
    public function setNote(?int $note): void
    {
        $this->note = $note;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return \User
     */
    public function getIdFormateur(): ?User
    {
        return $this->idFormateur;
    }

    /**
     * @param User $idFormateur
     */
    public function setIdFormateur(?User $idFormateur): void
    {
        $this->idFormateur = $idFormateur;
    }

    /**
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getIdEtudiant()
    {
        return $this->idEtudiant;
    }

    /**
     * @param \Doctrine\Common\Collections\Collection $idEtudiant
     */
    public function setIdEtudiant($idEtudiant): void
    {
        $this->idEtudiant = $idEtudiant;
    }

    public function addIdEtudiant(User $idEtudiant): self
    {
        if (!$this->idEtudiant->contains($idEtudiant)) {
            $this->idEtudiant[] = $idEtudiant;
        }

        return $this;
    }

    public function removeIdEtudiant(User $idEtudiant): self
    {
        $this->idEtudiant->removeElement($idEtudiant);

        return $this;
    }

   





}
