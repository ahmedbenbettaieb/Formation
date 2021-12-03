<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Note
 *
 * @ORM\Table(name="note", indexes={@ORM\Index(name="fk_idtest", columns={"id_test"}), @ORM\Index(name="fk_idetudiant", columns={"id_etudiant"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\NoteRepository")
 */
class Note
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
     * @var int
     *
     * @ORM\Column(name="note_obtenue", type="integer", nullable=false)
     */
    private $noteObtenue;

    /**
     * @var \Test
     *
     * @ORM\OneToOne(targetEntity="Test")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_test", referencedColumnName="id")
     * })
     */
    private $idTest;

    /**
     * @var \User
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_etudiant", referencedColumnName="id")
     * })
     */
    private $idEtudiant;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNoteObtenue(): ?int
    {
        return $this->noteObtenue;
    }

    public function setNoteObtenue(int $noteObtenue): self
    {
        $this->noteObtenue = $noteObtenue;

        return $this;
    }

    public function getIdTest(): ?Test
    {
        return $this->idTest;
    }

    public function setIdTest(?Test $idTest): self
    {
        $this->idTest = $idTest;

        return $this;
    }

    public function getIdEtudiant(): ?User
    {
        return $this->idEtudiant;
    }

    public function setIdEtudiant(?User $idEtudiant): self
    {
        $this->idEtudiant = $idEtudiant;

        return $this;
    }


}
