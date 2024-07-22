<?php

namespace App\Entity;

use App\Repository\ServicesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServicesRepository::class)]
#[ORM\Table(name:"services")] // nom exact de la table
class Services
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descript = null;

    #[ORM\OneToMany(targetEntity: ImgServices::class, mappedBy: 'services', orphanRemoval: true)]
    private Collection $imgServices;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDescript(): ?string
    {
        return $this->descript;
    }

    public function setDescript(string $descript): static
    {
        $this->descript = $descript;

        return $this;
    }

    public function getImgServices(): Collection
    {
        return $this->imgServices;
    }

    public function addImgService(ImgServices $imgServices): self
    {
        if (!$this->imgServices->contains($imgServices)) {
            $this->imgServices->add($imgServices);
            $imgServices->setservices($this);
        }

        return $this;
    }

    public function removeImgService(ImgServices $imgServices): self
    {
        if ($this->imgServices->removeElement($imgServices)) {
            if ($imgServices->getservices() === $this) {
                $imgServices->setservices(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom;
    }
}
