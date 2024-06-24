<?php

namespace App\Entity;

use App\Repository\ImgHabitatsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ImgHabitatsRepository::class)]
#[ORM\Table(name:"img_habitats")] // nom exact de la table
class ImgHabitats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::BLOB)]
    private $image;

    #[ORM\ManyToOne(inversedBy: 'imgHabitats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habitats $habitat = null;

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

    public function getHabitat(): ?Habitats
    {
        return $this->habitat;
    }

    public function setHabitat(?Habitats $habitat): self
    {
        $this->habitat = $habitat;

        return $this;
    }
}
