<?php

namespace App\Entity;

use App\Repository\ImgServicesRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: ImgServicesRepository::class)]
#[ORM\Table(name:"img_services")] // nom exact de la table
#[Vich\Uploadable]
class ImgServices
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'imgServices', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;


    #[ORM\ManyToOne(inversedBy: 'imgServices')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Services $services = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getservices(): ?services
    {
        return $this->services;
    }

    public function setservices(?services $services): self
    {
        $this->services = $services;

        return $this;
    }

    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;
    }

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageName(?string $imageName): void
    {
        $this->imageName = $imageName;
    }

    public function getImageName(): ?string
    {
        return $this->imageName;
    }

    public function __toString(): string
    {
        return $this->imageName;
    }
}
