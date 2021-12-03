<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Abonnement
 *
 * @ORM\Table(name="abonnement", indexes={@ORM\Index(name="fk_idetudiant_formation_abonnement", columns={"id_etudiant", "id_formation"}), @ORM\Index(name="IDX_351268BB21A5CE76", columns={"id_etudiant"})})
 * @ORM\Entity
* @ORM\Entity(repositoryClass="App\Repository\AbonnementRepository")
 */
class Abonnement
{
    /**
     * @var string
     *
     * @ORM\Column(name="date_abonnement", type="string", length=255, nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     *
     */
    private $dateAbonnement = 'CURRENT_TIMESTAMP';

    /**
     * @var bool
     *
     * @ORM\Column(name="rated", type="boolean", nullable=false)
     */
    private $rated;

    /**
     * @var \User
     *
     * @ORM\ManyToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_etudiant", referencedColumnName="id")
     * })
     */
    private $idEtudiant;

    /**
     * @var \Formation
     *
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="NONE")
     * @ORM\ManyToOne(targetEntity="Formation")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_formation", referencedColumnName="id")
     * })
     */
    private $idFormation;

    /**
     * @return string
     */
    public function getDateAbonnement(): string
    {
        return $this->dateAbonnement;
    }

    /**
     * @param string $dateAbonnement
     */
    public function setDateAbonnement(string $dateAbonnement): void
    {
        $this->dateAbonnement = $dateAbonnement;
    }

    /**
     * @return bool
     */
    public function isRated(): bool
    {
        return $this->rated;
    }

    /**
     * @param bool $rated
     */
    public function setRated(bool $rated): void
    {
        $this->rated = $rated;
    }

    /**
     * @return \User
     */
    public function getIdEtudiant(): \User
    {
        return $this->idEtudiant;
    }

    /**
     * @param \User $idEtudiant
     */
    public function setIdEtudiant(User $idEtudiant): void
    {
        $this->idEtudiant = $idEtudiant;
    }

    /**
     * @return \Formation
     */
    public function getIdFormation(): ?Formation
    {
        return $this->idFormation;
    }

    /**
     * @param \Formation $idFormation
     */
    public function setIdFormation(?Formation $idFormation): void
    {
        $this->idFormation = $idFormation;
    }

    public function getRated(): ?bool
    {
        return $this->rated;
    }


}
