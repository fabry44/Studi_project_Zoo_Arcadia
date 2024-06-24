<?php

namespace App\Entity;

use App\Repository\RapportsVeterinairesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RapportsVeterinairesRepository::class)]
#[ORM\Table(name:"rapports_veterinaires")] // nom exact de la table
class RapportsVeterinaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $etat = null;

    #[ORM\Column(length: 255)]
    private ?string $nourriture = null;

    #[ORM\Column]
    private ?float $grammage = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $detail = null;

    #[ORM\ManyToOne(inversedBy: 'rapportsVeterinaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animaux $animal = null;

    #[ORM\ManyToOne(inversedBy: 'rapportsVeterinaires')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Utilisateurs $veterinaire = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(string $etat): self
    {
        $this->etat = $etat;
        return $this;
    }

    public function getNourriture(): ?string
    {
        return $this->nourriture;
    }

    public function setNourriture(string $nourriture): self
    {
        $this->nourriture = $nourriture;
        return $this;
    }

    public function getGrammage(): ?float
    {
        return $this->grammage;
    }

    public function setGrammage(float $grammage): self
    {
        $this->grammage = $grammage;
        return $this;
    }

    public function getDetail(): ?string
    {
        return $this->detail;
    }

    public function setDetail(?string $detail): self
    {
        $this->detail = $detail;
        return $this;
    }

    public function getAnimal(): ?Animaux
    {
        return $this->animal;
    }

    public function setAnimal(?Animaux $animal): self
    {
        $this->animal = $animal;
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
}
