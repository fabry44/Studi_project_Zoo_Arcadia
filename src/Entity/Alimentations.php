<?php

namespace App\Entity;

use App\Repository\AlimentationsRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AlimentationsRepository::class)]
#[ORM\Table(name:"alimentations")] // nom exact de la table
class Alimentations
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: "datetime_immutable")]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 255)]
    private ?string $nourriture = null;

    #[ORM\Column(type: "float")]
    private ?float $quantite = null;

    #[ORM\ManyToOne(targetEntity: Animaux::class, inversedBy: 'alimentations')]
    #[ORM\JoinColumn(name: "animal_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Animaux $animal;

    #[ORM\ManyToOne(targetEntity: Utilisateurs::class, inversedBy: 'alimentations')]
    #[ORM\JoinColumn(name: "employe_id", referencedColumnName: "id", nullable: false, onDelete: "CASCADE")]
    private Utilisateurs $employe;

    public function __construct()
    {
        $this->animal = new Animaux();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): self
    {
        $this->date = $date;
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

    public function getQuantite(): ?float
    {
        return $this->quantite;
    }

    public function setQuantite(float $quantite): self
    {
        $this->quantite = $quantite;
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

    public function getEmploye(): ?Utilisateurs
    {
        return $this->employe;
    }

    public function setEmploye(?Utilisateurs $employe): self
    {
        $this->employe = $employe;
        return $this;
    }

    

    
}
