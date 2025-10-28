<?php

namespace App\Entity;

use App\Repository\RetourActionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: RetourActionRepository::class)]
class RetourAction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'Le retour d\'action est obligatoire')]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\Choice(choices: ['en_attente', 'validé', 'refusé'], message: 'Le statut doit être en_attente, validé ou refusé')]
    private ?string $statut = 'en_attente';

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaireValidation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $dateCreation = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $dateValidation = null;

    #[ORM\ManyToOne(targetEntity: Goudurix::class, inversedBy: 'retoursAction')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Le risque est obligatoire')]
    private ?Goudurix $risque = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'L\'auteur est obligatoire')]
    private ?User $auteur = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $validateur = null;

    public function __construct()
    {
        $this->dateCreation = new \DateTime();
        $this->statut = 'en_attente';
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getCommentaireValidation(): ?string
    {
        return $this->commentaireValidation;
    }

    public function setCommentaireValidation(?string $commentaireValidation): static
    {
        $this->commentaireValidation = $commentaireValidation;
        return $this;
    }

    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    public function setDateCreation(\DateTime $dateCreation): static
    {
        $this->dateCreation = $dateCreation;
        return $this;
    }

    public function getDateValidation(): ?\DateTime
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTime $dateValidation): static
    {
        $this->dateValidation = $dateValidation;
        return $this;
    }

    public function getRisque(): ?Goudurix
    {
        return $this->risque;
    }

    public function setRisque(?Goudurix $risque): static
    {
        $this->risque = $risque;
        return $this;
    }

    public function getAuteur(): ?User
    {
        return $this->auteur;
    }

    public function setAuteur(?User $auteur): static
    {
        $this->auteur = $auteur;
        return $this;
    }

    public function getValidateur(): ?User
    {
        return $this->validateur;
    }

    public function setValidateur(?User $validateur): static
    {
        $this->validateur = $validateur;
        return $this;
    }

    public function isEnAttente(): bool
    {
        return $this->statut === 'en_attente';
    }

    public function isValide(): bool
    {
        return $this->statut === 'validé';
    }

    public function isRefuse(): bool
    {
        return $this->statut === 'refusé';
    }
}
