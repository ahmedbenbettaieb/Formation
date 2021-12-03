<?php

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Quiz
 *
 * @ORM\Table(name="quiz", indexes={@ORM\Index(name="fk_idformateur_quiz", columns={"id_formateur"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\QuizRepository")
 */
class Quiz
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
     * @Assert\NotBlank(message="veuillez renseigner le sujet du quiz")
     */
    private $sujet;

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
     * @ORM\OneToMany(targetEntity="Questionquiz", mappedBy="idQuiz")
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
     * @return Collection|Questionquiz[]
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Questionquiz $question): self
    {
        if (!$this->questions->contains($question)) {
            $this->questions[] = $question;
            $question->setIdQuiz($this);
        }

        return $this;
    }

    public function removeQuestion(Questionquiz $question): self
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getIdQuiz() === $this) {
                $question->setIdQuiz(null);
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
