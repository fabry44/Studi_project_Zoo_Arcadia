<?php

namespace App\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

#[MongoDB\Document]
class Animal
{
    #[MongoDB\Id]
    private $id;

    #[MongoDB\Field(type: 'string')]
    private $prenom;

    #[MongoDB\Field(type: 'int')]
    private $vue = 0;

    // Getters et setters...

    public function getId(): ?string
    {
        return $this->id;
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

    public function getVue(): ?int
    {
        return $this->vue;
    }

    public function setVue(int $vue): self
    {
        $this->vue = $vue;
        return $this;
    }

    public function incrementVue(): self
    {
        $this->vue++;
        return $this;
    }
}
