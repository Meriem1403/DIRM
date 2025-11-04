<?php

namespace App\Entity;

use App\Repository\GoudurixRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use Symfony\Component\Validator\Constraints as Assert;
use App\Entity\NomRisque;

#[ORM\Entity(repositoryClass: GoudurixRepository::class)]
class Goudurix
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: 'Le titre est obligatoire')]
    #[Assert\Length(max: 255, maxMessage: 'Le titre ne peut pas dépasser {{ limit }} caractères')]
    private ?string $titre = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank(message: 'La description est obligatoire')]
    private ?string $description = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le niveau de risque est obligatoire')]
    #[Assert\Choice(choices: ['faible', 'moyen', 'élevé', 'critique'], message: 'Le niveau de risque doit être faible, moyen, élevé ou critique')]
    private ?string $niveauRisque = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message: 'Le statut est obligatoire')]
    #[Assert\Choice(choices: ['en_cours', 'traité', 'surveillé', 'archivé'], message: 'Le statut doit être en_cours, traité, surveillé ou archivé')]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message: 'La date de détection est obligatoire')]
    private ?\DateTime $dateDetection = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $dateResolution = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mesuresPreventives = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mesuresCorrectives = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $mesureEnCours = null;

    #[ORM\Column]
    private ?bool $mesureMiseEnPlace = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $retourAction = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $auteurRetour = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $dateRetour = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaires = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTime $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTime $updatedAt = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Le responsable est obligatoire')]
    private ?User $responsable = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?User $createur = null;

    #[ORM\ManyToOne(targetEntity: Service::class)]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotBlank(message: 'Le service est obligatoire')]
    private ?Service $service = null;

    #[ORM\ManyToOne(targetEntity: Lieu::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne(targetEntity: NomRisque::class, inversedBy: 'risques')]
    #[ORM\JoinColumn(nullable: true)]
    private ?NomRisque $nomRisque = null;

    #[ORM\ManyToMany(targetEntity: User::class)]
    #[ORM\JoinTable(name: 'goudurix_observateurs')]
    private Collection $observateurs;

    #[ORM\OneToMany(targetEntity: RetourAction::class, mappedBy: 'risque', cascade: ['persist', 'remove'])]
    private Collection $retoursAction;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $categorie = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $source = null;

    #[ORM\Column(nullable: true)]
    private ?int $probabilite = null;

    #[ORM\Column(nullable: true)]
    private ?int $gravite = null;

    #[ORM\Column(nullable: true)]
    private ?int $scoreRisque = null;

    // Colonnes additionnelles du CSV
    #[ORM\Column(name: 'idaction', length: 50, nullable: true)]
    private ?string $idaction = null;

    #[ORM\Column(name: 'id_situ_d', length: 50, nullable: true)]
    private ?string $idSituD = null;

    #[ORM\Column(name: 'id_dommage', length: 50, nullable: true)]
    private ?string $idDommage = null;

    #[ORM\Column(name: 'id_mesure', length: 50, nullable: true)]
    private ?string $idMesure = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $unite = null;

    #[ORM\Column(name: 'numero', length: 50, nullable: true)]
    private ?string $numero = null;

    #[ORM\Column(name: 'dommage', type: Types::TEXT, nullable: true)]
    private ?string $dommage = null;

    #[ORM\Column(name: 'etat', length: 50, nullable: true)]
    private ?string $etat = null;

    #[ORM\Column(name: 'periodicite', length: 100, nullable: true)]
    private ?string $periodicite = null;

    #[ORM\Column(name: 'prochain_controle', type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $prochainControle = null;

    #[ORM\Column(name: 'n_pdf', length: 100, nullable: true)]
    private ?string $nPdf = null;

    public function __construct()
    {
        $this->observateurs = new ArrayCollection();
        $this->retoursAction = new ArrayCollection();
        $this->createdAt = new \DateTime();
        $this->mesureMiseEnPlace = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): static
    {
        $this->titre = $titre;
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

    public function getNiveauRisque(): ?string
    {
        return $this->niveauRisque;
    }

    public function setNiveauRisque(string $niveauRisque): static
    {
        $this->niveauRisque = $niveauRisque;
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

    public function getDateDetection(): ?\DateTime
    {
        return $this->dateDetection;
    }

    public function setDateDetection(\DateTime $dateDetection): static
    {
        $this->dateDetection = $dateDetection;
        return $this;
    }

    public function getDateResolution(): ?\DateTime
    {
        return $this->dateResolution;
    }

    public function setDateResolution(?\DateTime $dateResolution): static
    {
        $this->dateResolution = $dateResolution;
        return $this;
    }

    public function getMesuresPreventives(): ?string
    {
        return $this->mesuresPreventives;
    }

    public function setMesuresPreventives(?string $mesuresPreventives): static
    {
        $this->mesuresPreventives = $mesuresPreventives;
        return $this;
    }

    public function getMesuresCorrectives(): ?string
    {
        return $this->mesuresCorrectives;
    }

    public function setMesuresCorrectives(?string $mesuresCorrectives): static
    {
        $this->mesuresCorrectives = $mesuresCorrectives;
        return $this;
    }

    public function getCommentaires(): ?string
    {
        return $this->commentaires;
    }

    public function setCommentaires(?string $commentaires): static
    {
        $this->commentaires = $commentaires;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTime $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTime $updatedAt): static
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function getResponsable(): ?User
    {
        return $this->responsable;
    }

    public function setResponsable(?User $responsable): static
    {
        $this->responsable = $responsable;
        return $this;
    }

    public function getCreateur(): ?User
    {
        return $this->createur;
    }

    public function setCreateur(?User $createur): static
    {
        $this->createur = $createur;
        return $this;
    }

    public function getService(): ?Service
    {
        return $this->service;
    }

    public function setService(?Service $service): static
    {
        $this->service = $service;
        return $this;
    }

    public function getLieu(): ?Lieu
    {
        return $this->lieu;
    }

    public function setLieu(?Lieu $lieu): static
    {
        $this->lieu = $lieu;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getObservateurs(): Collection
    {
        return $this->observateurs;
    }

    public function addObservateur(User $observateur): static
    {
        if (!$this->observateurs->contains($observateur)) {
            $this->observateurs->add($observateur);
        }
        return $this;
    }

    public function removeObservateur(User $observateur): static
    {
        $this->observateurs->removeElement($observateur);
        return $this;
    }

    public function getCategorie(): ?string
    {
        return $this->categorie;
    }

    public function setCategorie(?string $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): static
    {
        $this->source = $source;
        return $this;
    }

    public function getProbabilite(): ?int
    {
        return $this->probabilite;
    }

    public function setProbabilite(?int $probabilite): static
    {
        $this->probabilite = $probabilite;
        return $this;
    }

    public function getGravite(): ?int
    {
        return $this->gravite;
    }

    public function setGravite(?int $gravite): static
    {
        $this->gravite = $gravite;
        return $this;
    }

    public function getScoreRisque(): ?int
    {
        return $this->scoreRisque;
    }

    public function setScoreRisque(?int $scoreRisque): static
    {
        $this->scoreRisque = $scoreRisque;
        return $this;
    }

    public function calculateScoreRisque(): void
    {
        if ($this->probabilite !== null && $this->gravite !== null) {
            $this->scoreRisque = $this->probabilite * $this->gravite;
            
            // Déterminer le niveau de risque basé sur le score
            if ($this->scoreRisque >= 20) {
                $this->niveauRisque = 'critique';
            } elseif ($this->scoreRisque >= 15) {
                $this->niveauRisque = 'élevé';
            } elseif ($this->scoreRisque >= 10) {
                $this->niveauRisque = 'moyen';
            } else {
                $this->niveauRisque = 'faible';
            }
        }
    }

    public function __toString(): string
    {
        return $this->titre ?? 'Nouveau risque';
    }

    // Méthodes pour les mesures et retours d'action
    public function getMesureEnCours(): ?string
    {
        return $this->mesureEnCours;
    }

    public function setMesureEnCours(?string $mesureEnCours): static
    {
        $this->mesureEnCours = $mesureEnCours;
        return $this;
    }

    public function isMesureMiseEnPlace(): ?bool
    {
        return $this->mesureMiseEnPlace;
    }

    public function setMesureMiseEnPlace(bool $mesureMiseEnPlace): static
    {
        $this->mesureMiseEnPlace = $mesureMiseEnPlace;
        return $this;
    }

    public function getRetourAction(): ?string
    {
        return $this->retourAction;
    }

    public function setRetourAction(?string $retourAction): static
    {
        $this->retourAction = $retourAction;
        return $this;
    }

    public function getAuteurRetour(): ?User
    {
        return $this->auteurRetour;
    }

    public function setAuteurRetour(?User $auteurRetour): static
    {
        $this->auteurRetour = $auteurRetour;
        return $this;
    }

    public function getDateRetour(): ?\DateTime
    {
        return $this->dateRetour;
    }

    public function setDateRetour(?\DateTime $dateRetour): static
    {
        $this->dateRetour = $dateRetour;
        return $this;
    }

    /**
     * @return Collection<int, RetourAction>
     */
    public function getRetoursAction(): Collection
    {
        return $this->retoursAction;
    }

    public function addRetoursAction(RetourAction $retoursAction): static
    {
        if (!$this->retoursAction->contains($retoursAction)) {
            $this->retoursAction->add($retoursAction);
            $retoursAction->setRisque($this);
        }

        return $this;
    }

    public function removeRetoursAction(RetourAction $retoursAction): static
    {
        if ($this->retoursAction->removeElement($retoursAction)) {
            // set the owning side to null (unless already changed)
            if ($retoursAction->getRisque() === $this) {
                $retoursAction->setRisque(null);
            }
        }

        return $this;
    }

    public function getNomRisque(): ?NomRisque
    {
        return $this->nomRisque;
    }

    public function setNomRisque(?NomRisque $nomRisque): static
    {
        $this->nomRisque = $nomRisque;
        return $this;
    }

    // Getters/Setters pour les colonnes additionnelles du CSV
    public function getIdaction(): ?string
    {
        return $this->idaction;
    }

    public function setIdaction(?string $idaction): static
    {
        $this->idaction = $idaction;
        return $this;
    }

    public function getIdSituD(): ?string
    {
        return $this->idSituD;
    }

    public function setIdSituD(?string $idSituD): static
    {
        $this->idSituD = $idSituD;
        return $this;
    }

    public function getIdDommage(): ?string
    {
        return $this->idDommage;
    }

    public function setIdDommage(?string $idDommage): static
    {
        $this->idDommage = $idDommage;
        return $this;
    }

    public function getIdMesure(): ?string
    {
        return $this->idMesure;
    }

    public function setIdMesure(?string $idMesure): static
    {
        $this->idMesure = $idMesure;
        return $this;
    }

    public function getUnite(): ?string
    {
        return $this->unite;
    }

    public function setUnite(?string $unite): static
    {
        $this->unite = $unite;
        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(?string $numero): static
    {
        $this->numero = $numero;
        return $this;
    }

    public function getDommage(): ?string
    {
        return $this->dommage;
    }

    public function setDommage(?string $dommage): static
    {
        $this->dommage = $dommage;
        return $this;
    }

    public function getEtat(): ?string
    {
        return $this->etat;
    }

    public function setEtat(?string $etat): static
    {
        $this->etat = $etat;
        return $this;
    }

    public function getPeriodicite(): ?string
    {
        return $this->periodicite;
    }

    public function setPeriodicite(?string $periodicite): static
    {
        $this->periodicite = $periodicite;
        return $this;
    }

    public function getProchainControle(): ?\DateTime
    {
        return $this->prochainControle;
    }

    public function setProchainControle(?\DateTime $prochainControle): static
    {
        $this->prochainControle = $prochainControle;
        return $this;
    }

    public function getNPdf(): ?string
    {
        return $this->nPdf;
    }

    public function setNPdf(?string $nPdf): static
    {
        $this->nPdf = $nPdf;
        return $this;
    }
}
