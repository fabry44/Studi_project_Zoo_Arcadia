<?php

namespace App\Entity;

use App\Repository\AnimauxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AnimauxRepository::class)]
#[ORM\Table(name:"animaux")] // nom exact de la table
class Animaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\ManyToOne(inversedBy: 'animauxPresents')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habitats $habitat = null;

    #[ORM\ManyToOne(inversedBy: 'animaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Races $race = null;

    #[ORM\OneToMany(targetEntity: ImgAnimaux::class, mappedBy: 'animal', orphanRemoval: true)]
    private Collection $imgAnimaux;

    #[ORM\OneToMany(targetEntity: RapportsVeterinaires::class, mappedBy: 'animal', orphanRemoval: true)]
    private Collection $rapportsVeterinaires;

    #[ORM\OneToMany(targetEntity: Alimentations::class, mappedBy: 'animal', orphanRemoval: true)]
    private Collection $alimentations;

    public function __construct()
    {
        $this->imgAnimaux = new ArrayCollection();
        $this->rapportsVeterinaires = new ArrayCollection();
        $this->alimentations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getHabitat(): ?Habitats
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitats $habitat): self
    {
        $this->habitat = $habitat;
        return $this;
    }

    public function getRace(): ?Races
    {
        return $this->race;
    }

    public function setRace(?Races $race): self
    {
        $this->race = $race;
        return $this;
    }

    public function getImgAnimaux(): Collection
    {
        return $this->imgAnimaux;
    }

    public function addImgAnimal(ImgAnimaux $imgAnimaux): self
    {
        if (!$this->imgAnimaux->contains($imgAnimaux)) {
            $this->imgAnimaux[] = $imgAnimaux;
            $imgAnimaux->setAnimal($this);
        }
        return $this;
    }

    public function removeImgAnimal(ImgAnimaux $imgAnimaux): self
    {
        if ($this->imgAnimaux->removeElement($imgAnimaux)) {
            if ($imgAnimaux->getAnimal() === $this) {
                $imgAnimaux->setAnimal(null);
            }
        }
        return $this;
    }

    public function getRapportsVeterinaires(): Collection
    {
        return $this->rapportsVeterinaires;
    }

    public function addRapportsVeterinaire(RapportsVeterinaires $rapportsVeterinaire): self
    {
        if (!$this->rapportsVeterinaires->contains($rapportsVeterinaire)) {
            $this->rapportsVeterinaires[] = $rapportsVeterinaire;
            $rapportsVeterinaire->setAnimal($this);
        }
        return $this;
    }

    public function removeRapportsVeterinaire(RapportsVeterinaires $rapportsVeterinaire): self
    {
        if ($this->rapportsVeterinaires->removeElement($rapportsVeterinaire)) {
            if ($rapportsVeterinaire->getAnimal() === $this) {
                $rapportsVeterinaire->setAnimal(null);
            }
        }
        return $this;
    }

    public function getAlimentations(): Collection
    {
        return $this->alimentations;
    }

    public function addAlimentation(Alimentations $alimentation): self
    {
        if (!$this->alimentations->contains($alimentation)) {
            $this->alimentations[] = $alimentation;
            $alimentation->setAnimal($this);
        }
        return $this;
    }

    public function removeAlimentation(Alimentations $alimentation): self
    {
        if ($this->alimentations->removeElement($alimentation)) {
            if ($alimentation->getAnimal() === $this) {
                $alimentation->setAnimal(null);
            }
        }
        return $this;
    }

    public function __toString(): string
    {
        return $this->prenom;
    }
}
