<?php

namespace App\Entity;

use App\Repository\HabitatsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HabitatsRepository::class)]
#[ORM\Table(name:"habitats")] // nom exact de la table
class Habitats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: 'text')]
    private ?string $descript = null;

    #[ORM\OneToMany(targetEntity: ImgHabitats::class, mappedBy: 'habitat', orphanRemoval: true)]
    private Collection $imgHabitats;

    #[ORM\OneToMany(targetEntity: Animaux::class, mappedBy: 'habitat')]
    private Collection $animauxPresents;

    #[ORM\OneToMany(targetEntity: AvisHabitats::class, mappedBy: 'habitat')]
    private Collection $avisHabitats;

    public function __construct()
    {
        $this->imgHabitats = new ArrayCollection();
        $this->animauxPresents = new ArrayCollection();
        $this->avisHabitats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDescript(): ?string
    {
        return $this->descript;
    }

    public function setDescript(string $descript): self
    {
        $this->descript = $descript;
        return $this;
    }

    public function getImgHabitats(): Collection
    {
        return $this->imgHabitats;
    }

    public function addImgHabitat(ImgHabitats $imgHabitat): self
    {
        if (!$this->imgHabitats->contains($imgHabitat)) {
            $this->imgHabitats->add($imgHabitat);
            $imgHabitat->setHabitat($this);
        }

        return $this;
    }

    public function removeImgHabitat(ImgHabitats $imgHabitat): self
    {
        if ($this->imgHabitats->removeElement($imgHabitat)) {
            if ($imgHabitat->getHabitat() === $this) {
                $imgHabitat->setHabitat(null);
            }
        }

        return $this;
    }

    public function getAnimauxPresents(): Collection
    {
        return $this->animauxPresents;
    }

    public function addAnimauxPresent(Animaux $animaux): self
    {
        if (!$this->animauxPresents->contains($animaux)) {
            $this->animauxPresents->add($animaux);
            $animaux->setHabitat($this);
        }

        return $this;
    }

    public function removeAnimauxPresent(Animaux $animaux): self
    {
        if ($this->animauxPresents->removeElement($animaux)) {
            if ($animaux->getHabitat() === $this) {
                $animaux->setHabitat(null);
            }
        }

        return $this;
    }

    public function getAvisHabitats(): Collection
    {
        return $this->avisHabitats;
    }

    public function addAvisHabitat(AvisHabitats $avisHabitat): self
    {
        if (!$this->avisHabitats->contains($avisHabitat)) {
            $this->avisHabitats->add($avisHabitat);
            $avisHabitat->setHabitat($this);
        }

        return $this;
    }

    public function removeAvisHabitat(AvisHabitats $avisHabitat): self
    {
        if ($this->avisHabitats->removeElement($avisHabitat)) {
            if ($avisHabitat->getHabitat() === $this) {
                $avisHabitat->setHabitat(null);
            }
        }

        return $this;
    }
}
