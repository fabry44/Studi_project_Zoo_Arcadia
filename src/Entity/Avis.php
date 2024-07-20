<?php

namespace App\Entity;

use App\Repository\AvisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: AvisRepository::class)]
#[ORM\Table(name:"avis")] // nom exact de la table
class Avis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le pseudo est obligatoire')]
    #[Assert\Length(max: 255, maxMessage: 'Le pseudo ne doit pas dépasser 255 caractères')]
    private ?string $pseudo = null;

    #[ORM\Column(type: Types::SMALLINT)]
    #[Assert\NotBlank(message: 'La note est obligatoire')]
    #[Assert\Range(min: 1, max: 5, notInRangeMessage: 'La note doit être entre {{ min }} et {{ max }}')]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'L\'avis est obligatoire')]
    #[Assert\Length(max: 500, maxMessage: 'L\'avis ne doit pas dépasser 500 caractères')]
    private ?string $avis = null;

    #[ORM\Column]
    private ?bool $valide = null;

    #[ORM\ManyToOne(inversedBy: 'avis')]
    private ?Utilisateurs $employe = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;
        return $this;
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

    public function isValide(): ?bool
    {
        return $this->valide;
    }

    public function setValide(bool $valide): self
    {
        $this->valide = $valide;
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

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }
}
