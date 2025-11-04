<?php

namespace App\Entity;

use App\Repository\NomRisqueRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: NomRisqueRepository::class)]
class NomRisque
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, unique: true)]
    #[Assert\NotBlank(message: 'Le nom du risque est obligatoire')]
    #[Assert\Length(max: 255, maxMessage: 'Le nom ne peut pas dépasser {{ limit }} caractères')]
    private ?string $nom = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    #[ORM\Column]
    private ?bool $actif = true;

    #[ORM\OneToMany(targetEntity: Goudurix::class, mappedBy: 'nomRisque')]
    private Collection $risques;

    public function __construct()
    {
        $this->risques = new ArrayCollection();
    }

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

    public function isActif(): ?bool
    {
        return $this->actif;
    }

    public function setActif(bool $actif): static
    {
        $this->actif = $actif;
        return $this;
    }

    /**
     * @return Collection<int, Goudurix>
     */
    public function getRisques(): Collection
    {
        return $this->risques;
    }

    public function addRisque(Goudurix $risque): static
    {
        if (!$this->risques->contains($risque)) {
            $this->risques->add($risque);
            $risque->setNomRisque($this);
        }

        return $this;
    }

    public function removeRisque(Goudurix $risque): static
    {
        if ($this->risques->removeElement($risque)) {
            // set the owning side to null (unless already changed)
            if ($risque->getNomRisque() === $this) {
                $risque->setNomRisque(null);
            }
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? 'Nouveau nom de risque';
    }
}

