<?php

namespace App\Entity;

use App\Repository\UtilisateursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UtilisateursRepository::class)]
#[ORM\Table(name:"utilisateurs")] // nom exact de la table
class Utilisateurs
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;
    
    #[ORM\Column(length: 255)]
    #[Assert\Choice(choices: ['administrateur', 'veterinaire', 'employe'], message: 'Choisissez un rôle valide.')]
    private ?string $role = null;

    #[ORM\OneToMany(targetEntity: Avis::class, mappedBy: 'employe')]
    private Collection $avis;

    #[ORM\OneToMany(targetEntity: RapportsVeterinaires::class, mappedBy: 'veterinaire')]
    private Collection $rapportsVeterinaires;

    #[ORM\OneToMany(targetEntity: AvisHabitats::class, mappedBy: 'veterinaire')]
    private Collection $avisHabitats;

    #[ORM\OneToMany(targetEntity: Alimentations::class, mappedBy: 'employe')]
    private Collection $alimentations;

    public function __construct()
    {
        $this->avis = new ArrayCollection();
        $this->rapportsVeterinaires = new ArrayCollection();
        $this->avisHabitats = new ArrayCollection();
        $this->alimentations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): self
    {
        $this->role = $role;
        return $this;
    }

    public function getAvis(): Collection
    {
        return $this->avis;
    }

    public function addAvis(Avis $avis): self
    {
        if (!$this->avis->contains($avis)) {
            $this->avis[] = $avis;
            $avis->setEmploye($this);
        }
        return $this;
    }

    public function removeAvis(Avis $avis): self
    {
        if ($this->avis->removeElement($avis)) {
            // set the owning side to null (unless already changed)
            if ($avis->getEmploye() === $this) {
                $avis->setEmploye(null);
            }
        }
        return $this;
    }

    public function getRapportsVeterinaires(): Collection
    {
        return $this->rapportsVeterinaires;
    }

    public function addRapportVeterinaire(RapportsVeterinaires $rapport): self
    {
        if (!$this->rapportsVeterinaires->contains($rapport)) {
            $this->rapportsVeterinaires[] = $rapport;
            $rapport->setVeterinaire($this);
        }
        return $this;
    }

    public function removeRapportVeterinaire(RapportsVeterinaires $rapport): self
    {
        if ($this->rapportsVeterinaires->removeElement($rapport)) {
            if ($rapport->getVeterinaire() === $this) {
                $rapport->setVeterinaire(null);
            }
        }
        return $this;
    }

    public function getAvisHabitats(): Collection
    {
        return $this->avisHabitats;
    }

    public function addAvisHabitat(AvisHabitats $avisHabitat): self
    {
        if (!$this->avisHabitats->contains($avisHabitat)) {
            $this->avisHabitats[] = $avisHabitat;
            $avisHabitat->setVeterinaire($this);
        }
        return $this;
    }

    public function removeAvisHabitat(AvisHabitats $avisHabitat): self
    {
        if ($this->avisHabitats->removeElement($avisHabitat)) {
            if ($avisHabitat->getVeterinaire() === $this) {
                $avisHabitat->setVeterinaire(null);
            }
        }
        return $this;
    }

    public function getAlimentations(): Collection
    {
        return $this->alimentations;
    }

    public function addAlimentation(Alimentations $alimentation): self
    {
        if (!$this->alimentations->contains($alimentation)) {
            $this->alimentations[] = $alimentation;
            $alimentation->setEmploye($this);
        }
        return $this;
    }

    public function removeAlimentation(Alimentations $alimentation): self
    {
        if ($this->alimentations->removeElement($alimentation)) {
            if ($alimentation->getEmploye() === $this) {
                $alimentation->setEmploye(null);
            }
        }
        return $this;
    }
}
