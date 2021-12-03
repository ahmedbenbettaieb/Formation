<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Test
 *
 * @ORM\Table(name="test", indexes={@ORM\Index(name="fk_formation", columns={"id_formation"}), @ORM\Index(name="fk_idformateur_test", columns={"id_formateur"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\TestRepository")
 */
class Test
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
     * @ORM\Column(name="sujet", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="veuillez renseigner le sujet du Test")
     */
    private $sujet;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_etudiant_passes", type="integer", nullable=false)
     */
    private $nbEtudiantPasses;

    /**
     * @var int
     *
     * @ORM\Column(name="nb_etudiants_admis", type="integer", nullable=false)
     */
    private $nbEtudiantsAdmis;

    /**
     * @var int
     *
     * @ORM\Column(name="duree", type="integer", nullable=false)
     * @Assert\NotBlank(message="veuillez entrer la durrée(min) du Test")
     *@Assert\GreaterThan(value = 14,message = "durée doit etre supérieur 15")
     */
    private $duree;

    /**
     * @var \Formation
     *
     * @ORM\ManyToOne(targetEntity="Formation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_formation", referencedColumnName="id")
     * })
     */
    private $idFormation;

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
     * @ORM\OneToMany(targetEntity="Questiontest", mappedBy="idTest")
     */
    private $questions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSujet(): ?string
    {
        return $this->sujet;
    }

    public function setSujet(?string $sujet): self
    {
        $this->sujet = $sujet;

        return $this;
    }

    public function getNbEtudiantPasses(): ?int
    {
        return $this->nbEtudiantPasses;
    }

    public function setNbEtudiantPasses(int $nbEtudiantPasses): self
    {
        $this->nbEtudiantPasses = $nbEtudiantPasses;

        return $this;
    }

    public function getNbEtudiantsAdmis(): ?int
    {
        return $this->nbEtudiantsAdmis;
    }

    public function setNbEtudiantsAdmis(int $nbEtudiantsAdmis): self
    {
        $this->nbEtudiantsAdmis = $nbEtudiantsAdmis;

        return $this;
    }

    public function getDuree(): ?int
    {
        return $this->duree;
    }

    public function setDuree(?int $duree): self
    {
        $this->duree = $duree;

        return $this;
    }

    public function getIdFormation(): ?Formation
    {
        return $this->idFormation;
    }

    public function setIdFormation(?Formation $idFormation): self
    {
        $this->idFormation = $idFormation;

        return $this;
    }

    public function getIdFormateur(): ?User
    {
        return $this->idFormateur;
    }

    public function setIdFormateur(?User $idFormateur): self
    {
        $this->idFormateur = $idFormateur;

        return $this;
    }

    /**
     * @return Collection|Questiontest[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Questiontest $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setIdTest($this);
        }

        return $this;
    }

    public function removeQuestion(Questiontest $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getIdTest() === $this) {
                $question->setIdTest(null);
            }
        }

        return $this;
    }

    public function getTotalPoint() :?int
    {
        $note = 0 ;
        foreach($this->questions as $question)
        {
            $note = ($note + $question->getNote()) ;
        }

        return $note ;
    }


}
