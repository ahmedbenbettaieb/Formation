<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Slide
 *
 * @ORM\Table(name="slide", indexes={@ORM\Index(name="fk_idformation_slide", columns={"id_formation"})})
 * @ORM\Entity
 * @ORM\Entity(repositoryClass="App\Repository\SlideRepository")
 */
class Slide
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
     * @ORM\Column(name="video_slide", type="string", length=255, nullable=false)
     */
    private $videoSlide;

    /**
     * @var string
     *
     * @ORM\Column(name="image_slide", type="string", length=255, nullable=false)
     */
    private $imageSlide;

    /**
     * @var string
     *
     * @ORM\Column(name="text_slide", type="string", length=255, nullable=false)
     */
    private $textSlide;

    /**
     * @var int
     *
     * @ORM\Column(name="ordre", type="integer", nullable=false)
     */
    private $ordre;

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
    public function getVideoSlide(): ?string
    {
        return $this->videoSlide;
    }

    /**
     * @param string $videoSlide
     */
    public function setVideoSlide(?string $videoSlide): void
    {
        $this->videoSlide = $videoSlide;
    }

    /**
     * @return string
     */
    public function getImageSlide(): ?string
    {
        return $this->imageSlide;
    }

    /**
     * @param string $imageSlide
     */
    public function setImageSlide(?string $imageSlide): void
    {
        $this->imageSlide = $imageSlide;
    }

    /**
     * @return string
     */
    public function getTextSlide(): ?string
    {
        return $this->textSlide;
    }

    /**
     * @param string $textSlide
     */
    public function setTextSlide(?string $textSlide): void
    {
        $this->textSlide = $textSlide;
    }

    /**
     * @return int
     */
    public function getOrdre(): ?int
    {
        return $this->ordre;
    }

    /**
     * @param int $ordre
     */
    public function setOrdre(?int $ordre): void
    {
        $this->ordre = $ordre;
    }

    /**
     * @return \Formation
     */
    public function getIdFormation(): ?Formation
    {
        return $this->idFormation;
    }

    /**
     * @param Formation $idFormation
     */
    public function setIdFormation(?Formation $idFormation): void
    {
        $this->idFormation = $idFormation;
    }
    /**
     * @var string
     *
     */
    private $contenu;

    /**
     * @return string
     */
    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    /**
     * @param string $contenu
     */
    public function setContenu(?string $contenu): void
    {
        $this->contenu = $contenu;
    }


}
