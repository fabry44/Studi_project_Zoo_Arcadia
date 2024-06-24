<?php

namespace App\Entity;

use App\Repository\ImgAnimauxRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImgAnimauxRepository::class)]
#[ORM\Table(name:"img_animaux")] // nom exact de la table
class ImgAnimaux
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BLOB)]
    private $image;

    #[ORM\ManyToOne(inversedBy: 'imgAnimaux')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Animaux $animal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image): self
    {
        $this->image = $image;

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
}
