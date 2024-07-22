<?php

namespace App\Entity;

use App\Repository\ImgHabitatsRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;



#[ORM\Entity(repositoryClass: ImgHabitatsRepository::class)]
#[ORM\Table(name:"img_habitats")] // nom exact de la table
#[Vich\Uploadable]
class ImgHabitats
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // NOTE: This is not a mapped field of entity metadata, just a simple property.
    #[Vich\UploadableField(mapping: 'imgHabitats', fileNameProperty: 'imageName')]
    private ?File $imageFile = null;

    #[ORM\Column(nullable: true)]
    private ?string $imageName = null;

    #[ORM\ManyToOne(inversedBy: 'imgHabitats')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Habitats $habitat = null;

    public function getId(): ?int
    {
        return $this->id;
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

    // #[ORM\Column(nullable: true)]
    // private ?\DateTimeImmutable $updatedAt = null;// Pas de mise a jour prévue pour le moment

    /**
     * Si vous téléchargez manuellement un fichier (c'est-à-dire sans utiliser le formulaire Symfony), assurez-vous qu'une instance
     * de 'UploadedFile' est injectée dans ce setter pour déclencher la mise à jour. Si le paramètre de configuration de ce bundle 'inject_on_load' est défini sur 'true', ce setter
     * doit être capable d'accepter une instance de 'File' car le bundle en injectera une ici
     * pendant l'hydratation de Doctrine.
     *
     * @param File|\Symfony\Component\HttpFoundation\File\UploadedFile|null $imageFile
     */
    public function setImageFile(?File $imageFile = null): void
    {
        $this->imageFile = $imageFile;

        // if (null !== $imageFile) {
        //     // Il est nécessaire qu'au moins un champ change si vous utilisez doctrine
        //     // sinon les écouteurs d'événements ne seront pas appelés et le fichier sera perdu
        //     $this->updatedAt = new \DateTimeImmutable();
        // }
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
        return $this->habitat;
    }
}
