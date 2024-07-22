<?php

namespace App\Entity;

use App\Repository\HoraireRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HoraireRepository::class)]
class Horaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $jour = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $ouvre = null;

    #[ORM\Column(type: Types::TIME_MUTABLE)]
    private ?\DateTimeInterface $ferme = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getJour(): ?string
    {
        return $this->jour;
    }

    public function setJour(string $jour): static
    {
        $this->jour = $jour;

        return $this;
    }

    public function getOuvre(): ?\DateTimeInterface
    {
        return $this->ouvre;
    }

    public function setOuvre(\DateTimeInterface $ouvre): static
    {
        $this->ouvre = $ouvre;

        return $this;
    }

    public function getFerme(): ?\DateTimeInterface
    {
        return $this->ferme;
    }

    public function setFerme(\DateTimeInterface $ferme): static
    {
        $this->ferme = $ferme;

        return $this;
    }
}
