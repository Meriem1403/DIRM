<?php

namespace App\Entity;

use App\Repository\PersonneABordRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonneABordRepository::class)]
class PersonneABord
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $equipage = null;

    #[ORM\Column(nullable: true)]
    private ?int $passagers = null;

    #[ORM\Column(nullable: true)]
    private ?int $personnes = null;

    #[ORM\ManyToOne(inversedBy: 'personnesABord')]
    #[ORM\JoinColumn(nullable: false)]
    private ?DeclarationChantier $declarationChantier = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEquipage(): ?int
    {
        return $this->equipage;
    }

    public function setEquipage(?int $equipage): static
    {
        $this->equipage = $equipage;
        return $this;
    }

    public function getPassagers(): ?int
    {
        return $this->passagers;
    }

    public function setPassagers(?int $passagers): static
    {
        $this->passagers = $passagers;
        return $this;
    }

    public function getPersonnes(): ?int
    {
        return $this->personnes;
    }

    public function setPersonnes(?int $personnes): static
    {
        $this->personnes = $personnes;
        return $this;
    }

    public function getDeclarationChantier(): ?DeclarationChantier
    {
        return $this->declarationChantier;
    }

    public function setDeclarationChantier(?DeclarationChantier $declarationChantier): static
    {
        $this->declarationChantier = $declarationChantier;
        return $this;
    }

    public function __toString(): string
    {
        return 'Équipage: ' . $this->equipage . ', Passagers: ' . $this->passagers . ', Autres: ' . $this->personnes;
    }
}
