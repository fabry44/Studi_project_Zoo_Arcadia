<?php

namespace App\Entity;

use App\Repository\AvisHabitatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AvisHabitatsRepository::class)]
#[ORM\Table(name:"avis_habitats")] // nom exact de la table
class AvisHabitats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $avis = null;

    #[ORM\ManyToOne(inversedBy: 'avisHabitats')]
    private ?Habitats $habitat = null;

    #[ORM\ManyToOne(inversedBy: 'avisHabitats')]
    private ?Utilisateurs $veterinaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAvis(): ?string
    {
        return $this->avis;
    }

    public function setAvis(string $avis): self
    {
        $this->avis = $avis;
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

    public function getVeterinaire(): ?Utilisateurs
    {
        return $this->veterinaire;
    }

    public function setVeterinaire(?Utilisateurs $veterinaire): self
    {
        $this->veterinaire = $veterinaire;
        return $this;
    }

    public function __toString(): string
    {
        return $this->avis;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }
}
