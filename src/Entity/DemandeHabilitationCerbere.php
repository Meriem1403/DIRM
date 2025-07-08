<?php

namespace App\Entity;

use App\Repository\DemandeHabilitationCerbereRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;

#[ORM\Entity(repositoryClass: DemandeHabilitationCerbereRepository::class)]
class DemandeHabilitationCerbere
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $agent = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $demandeur = null;

    /**
     * @var Collection<int, ApplicationCerbere>
     */
    #[ORM\ManyToMany(targetEntity: ApplicationCerbere::class)]
    private Collection $applications;

    #[ORM\Column(length: 255)]
    private ?string $no = null;

    /**
     * @var Collection<int, ProfilCerbere>
     */
    #[ORM\ManyToMany(targetEntity: ProfilCerbere::class)]
    private Collection $profils;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $reglePortee = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $restrictions = null;

    #[ORM\Column]
    private ?DateTimeImmutable $dateSoumission = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\ManyToOne]
    private ?User $validePar = null;

    #[ORM\Column(nullable: true)]
    private ?DateTimeImmutable $dateValidation = null;

    public function __construct()
    {
        $this->applications = new ArrayCollection();
        $this->profils = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgent(): ?User
    {
        return $this->agent;
    }

    public function setAgent(?User $agent): static
    {
        $this->agent = $agent;
        return $this;
    }

    public function getDemandeur(): ?User
    {
        return $this->demandeur;
    }

    public function setDemandeur(?User $demandeur): static
    {
        $this->demandeur = $demandeur;
        return $this;
    }

    /**
     * @return Collection<int, ApplicationCerbere>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(ApplicationCerbere $application): static
    {
        if (!$this->applications->contains($application)) {
            $this->applications->add($application);
        }

        return $this;
    }

    public function removeApplication(ApplicationCerbere $application): static
    {
        $this->applications->removeElement($application);
        return $this;
    }

    public function getNo(): ?string
    {
        return $this->no;
    }

    public function setNo(string $no): static
    {
        $this->no = $no;
        return $this;
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
        }

        return $this;
    }

    public function removeProfil(ProfilCerbere $profil): static
    {
        $this->profils->removeElement($profil);
        return $this;
    }

    public function getReglePortee(): ?string
    {
        return $this->reglePortee;
    }

    public function setReglePortee(?string $reglePortee): static
    {
        $this->reglePortee = $reglePortee;
        return $this;
    }

    public function getRestrictions(): ?string
    {
        return $this->restrictions;
    }

    public function setRestrictions(?string $restrictions): static
    {
        $this->restrictions = $restrictions;
        return $this;
    }

    public function getDateSoumission(): ?DateTimeImmutable
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(DateTimeImmutable $dateSoumission): static
    {
        $this->dateSoumission = $dateSoumission;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getValidePar(): ?User
    {
        return $this->validePar;
    }

    public function setValidePar(?User $validePar): static
    {
        $this->validePar = $validePar;
        return $this;
    }

    public function getDateValidation(): ?DateTimeImmutable
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?DateTimeImmutable $dateValidation): static
    {
        $this->dateValidation = $dateValidation;
        return $this;
    }

    public function __toString(): string
    {
        return 'Demande n°' . ($this->no ?? $this->id);
    }
}
