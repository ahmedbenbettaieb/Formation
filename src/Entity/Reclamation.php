<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * Reclamation
 *
 * @ORM\Table(name="reclamation", indexes={@ORM\Index(name="fk_user_rec", columns={"id_user_rec"})})
 * @ORM\Entity
 */
class Reclamation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     * @Groups("reclamation")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="statut", type="string", length=255, nullable=false)
     * @Groups("reclamation")
     */
    private $statut;

    /**
     * @var string
     *
     * @ORM\Column(name="sujet_reclamation", type="string", length=255, nullable=false)
     * @Groups("reclamation")
     */
    private $sujetReclamation;

    /**
     * @var int
     *
     * @ORM\Column(name="admin_trait", type="integer", nullable=false)
     * @Groups("reclamation")
     */
    private $adminTrait;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="string", length=255, nullable=false)
     * @Assert\NotBlank(message="Contenu est requis")
     * @Groups("reclamation")
     */
    private $contenu;

    /**
     * @var \User
     *
     * @ORM\OneToOne(targetEntity="User")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_user_rec", referencedColumnName="id")
     * })
     * @Groups("reclamation")
     */
    private $idUserRec;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function getSujetReclamation(): ?string
    {
        return $this->sujetReclamation;
    }

    public function getAdminTrait(): ?int
    {
        return $this->adminTrait;
    }

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function getIdUserRec(): ?User
    {
        return $this->idUserRec;
    }

    public function setStatut(string $stat): self
    {
        $this->statut = $stat;

        return $this;
    }

    public function setSujet(string $suj): self
    {
        $this->sujetReclamation = $suj;

        return $this;
    }

    public function setContenu(string $cont): self
    {
        $this->contenu = $cont;

        return $this;
    }

    public function setAdminTrait(int $adm): self
    {
        $this->adminTrait = $adm;

        return $this;
    }

    public function setIdUser(User $usr): self
    {
        $this->idUserRec = $usr;

        return $this;
    }

}
