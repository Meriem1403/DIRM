<?php

namespace App\Entity;

use App\Repository\ApplicationCerbereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ApplicationCerbereRepository::class)]
class ApplicationCerbere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $code = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    /**
     * @var Collection<int, ProfilCerbere>
     */
    #[ORM\OneToMany(targetEntity: ProfilCerbere::class, mappedBy: 'application')]
    private Collection $profils;

    public function __construct()
    {
        $this->profils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;
        return $this;
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

    public function setDescription(string $description): static
    {
        $this->description = $description;
        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? $this->code ?? 'Application';
    }

    /**
     * @return Collection<int, ProfilCerbere>
     */
    public function getProfils(): Collection
    {
        return $this->profils;
    }

    public function addProfil(ProfilCerbere $profil): static
    {
        if (!$this->profils->contains($profil)) {
            $this->profils->add($profil);
            $profil->setApplication($this);
        }

        return $this;
    }

    public function removeProfil(ProfilCerbere $profil): static
    {
        if ($this->profils->removeElement($profil)) {
            if ($profil->getApplication() === $this) {
                $profil->setApplication(null);
            }
        }

        return $this;
    }
}
