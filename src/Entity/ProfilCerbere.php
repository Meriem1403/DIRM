<?php

namespace App\Entity;

use App\Repository\ProfilCerbereRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfilCerbereRepository::class)]
class ProfilCerbere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'profils')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ApplicationCerbere $application = null;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function getApplication(): ?ApplicationCerbere
    {
        return $this->application;
    }

    public function setApplication(?ApplicationCerbere $application): static
    {
        $this->application = $application;
        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? 'Profil';
    }
}
