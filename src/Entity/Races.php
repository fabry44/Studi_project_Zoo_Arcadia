<?php

namespace App\Entity;

use App\Repository\RacesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RacesRepository::class)]
#[ORM\Table(name:"races")] // nom exact de la table
class Races
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    /**
     * @var Collection<int, Animaux> Animaux appartenant à cette race
     */
    #[ORM\OneToMany(targetEntity: Animaux::class, mappedBy: 'race', orphanRemoval: true)]
    private Collection $animaux;

    public function __construct()
    {
        $this->animaux = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    /**
     * Retourne la collection d'animaux de cette race.
     */
    public function getAnimaux(): Collection
    {
        return $this->animaux;
    }

    public function addAnimaux(Animaux $animaux): self
    {
        if (!$this->animaux->contains($animaux)) {
            $this->animaux->add($animaux);
            $animaux->setRace($this);
        }
        return $this;
    }

    public function removeAnimaux(Animaux $animaux): self
    {
        if ($this->animaux->removeElement($animaux)) {
            if ($animaux->getRace() === $this) {
                $animaux->setRace(null);
            }
        }
        return $this;
    }
    
    public function __toString(): string
    {
        return $this->label;
    }
}
