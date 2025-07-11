<?php

namespace App\Entity;

use App\Repository\DemandeMobiliteRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use DateTimeInterface;

#[ORM\Entity(repositoryClass: DemandeMobiliteRepository::class)]
class DemandeMobilite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Agent concerné
    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $statutAgent = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null; // en_attente / traitee / refusee

    #[ORM\Column(length: 100)]
    private ?string $corps = null;

    #[ORM\Column(length: 100)]
    private ?string $grade = null;

    // Objet de la demande
    #[ORM\Column(length: 50)]
    private ?string $typeDemande = null; // arrivée / départ

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $ministereOrigine = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $directionOrigine = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $serviceOrigine = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $serviceActuel = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $dateDepart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $motifDepart = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $natureMutation = null; // interne / externe / retraite

    // Affectation
    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?DateTimeInterface $datePrisePoste = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $serviceAffectation = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $siteGeographique = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $bureau = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $fonction = null;

    // Type de poste
    #[ORM\Column(nullable: true)]
    private ?bool $posteRemplacement = null;

    #[ORM\Column(nullable: true)]
    private ?bool $posteCreation = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenomRemplace = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomRemplace = null;

    // Besoins particuliers
    #[ORM\Column(nullable: true)]
    private ?bool $besoinMobilier = null;

    #[ORM\Column(nullable: true)]
    private ?bool $besoinFournitures = null;

    #[ORM\Column(nullable: true)]
    private ?bool $besoinInformatique = null;

    // Questions diverses
    #[ORM\Column(length: 3, nullable: true)]
    private ?string $carteANTS = null; // oui/non

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $carteAchats = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $chargeVoyages = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $correspondantBudgetaire = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $encadreAgents = null;

    #[ORM\Column(length: 3, nullable: true)]
    private ?string $utiliseChorus = null;

    // Suivi

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $createdBy = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $commentaire = null;

    public function __toString(): string
    {
        return $this->nom . ' ' . $this->prenom . ' - ' . $this->typeDemande;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getCorps(): ?string
    {
        return $this->corps;
    }

    public function setCorps(string $corps): static
    {
        $this->corps = $corps;
        return $this;
    }

    public function getGrade(): ?string
    {
        return $this->grade;
    }

    public function setGrade(string $grade): static
    {
        $this->grade = $grade;
        return $this;
    }

    public function getTypeDemande(): ?string
    {
        return $this->typeDemande;
    }

    public function setTypeDemande(string $typeDemande): static
    {
        $this->typeDemande = $typeDemande;
        return $this;
    }

    public function getMinistereOrigine(): ?string
    {
        return $this->ministereOrigine;
    }

    public function setMinistereOrigine(?string $ministereOrigine): static
    {
        $this->ministereOrigine = $ministereOrigine;
        return $this;
    }

    public function getDirectionOrigine(): ?string
    {
        return $this->directionOrigine;
    }

    public function setDirectionOrigine(?string $directionOrigine): static
    {
        $this->directionOrigine = $directionOrigine;
        return $this;
    }

    public function getServiceOrigine(): ?string
    {
        return $this->serviceOrigine;
    }

    public function setServiceOrigine(?string $serviceOrigine): static
    {
        $this->serviceOrigine = $serviceOrigine;
        return $this;
    }

    public function getServiceActuel(): ?string
    {
        return $this->serviceActuel;
    }

    public function setServiceActuel(?string $serviceActuel): static
    {
        $this->serviceActuel = $serviceActuel;
        return $this;
    }

    public function getDateDepart(): ?DateTimeInterface
    {
        return $this->dateDepart;
    }

    public function setDateDepart(?DateTimeInterface $dateDepart): static
    {
        $this->dateDepart = $dateDepart;
        return $this;
    }

    public function getMotifDepart(): ?string
    {
        return $this->motifDepart;
    }

    public function setMotifDepart(?string $motifDepart): static
    {
        $this->motifDepart = $motifDepart;
        return $this;
    }

    public function getNatureMutation(): ?string
    {
        return $this->natureMutation;
    }

    public function setNatureMutation(?string $natureMutation): static
    {
        $this->natureMutation = $natureMutation;
        return $this;
    }

    public function getDatePrisePoste(): ?DateTimeInterface
    {
        return $this->datePrisePoste;
    }

    public function setDatePrisePoste(?DateTimeInterface $datePrisePoste): static
    {
        $this->datePrisePoste = $datePrisePoste;
        return $this;
    }
    public function getStatutAgent(): ?string
    {
        return $this->statutAgent;
    }

    public function setStatutAgent(string $statutAgent): static
    {
        $this->statutAgent = $statutAgent;
        return $this;
    }

    public function getServiceAffectation(): ?string
    {
        return $this->serviceAffectation;
    }

    public function setServiceAffectation(?string $serviceAffectation): static
    {
        $this->serviceAffectation = $serviceAffectation;
        return $this;
    }

    public function getSiteGeographique(): ?string
    {
        return $this->siteGeographique;
    }

    public function setSiteGeographique(?string $siteGeographique): static
    {
        $this->siteGeographique = $siteGeographique;
        return $this;
    }

    public function getBureau(): ?string
    {
        return $this->bureau;
    }

    public function setBureau(?string $bureau): static
    {
        $this->bureau = $bureau;
        return $this;
    }

    public function getFonction(): ?string
    {
        return $this->fonction;
    }

    public function setFonction(?string $fonction): static
    {
        $this->fonction = $fonction;
        return $this;
    }

    public function isPosteRemplacement(): ?bool
    {
        return $this->posteRemplacement;
    }

    public function setPosteRemplacement(?bool $posteRemplacement): static
    {
        $this->posteRemplacement = $posteRemplacement;

        return $this;
    }

    public function isPosteCreation(): ?bool
    {
        return $this->posteCreation;
    }

    public function setPosteCreation(?bool $posteCreation): static
    {
        $this->posteCreation = $posteCreation;

        return $this;
    }

    public function getPrenomRemplace(): ?string
    {
        return $this->prenomRemplace;
    }

    public function setPrenomRemplace(?string $prenomRemplace): static
    {
        $this->prenomRemplace = $prenomRemplace;

        return $this;
    }

    public function getNomRemplace(): ?string
    {
        return $this->nomRemplace;
    }

    public function setNomRemplace(?string $nomRemplace): static
    {
        $this->nomRemplace = $nomRemplace;

        return $this;
    }

    public function isBesoinMobilier(): ?bool
    {
        return $this->besoinMobilier;
    }

    public function setBesoinMobilier(?bool $besoinMobilier): static
    {
        $this->besoinMobilier = $besoinMobilier;

        return $this;
    }

    public function isBesoinFournitures(): ?bool
    {
        return $this->besoinFournitures;
    }

    public function setBesoinFournitures(?bool $besoinFournitures): static
    {
        $this->besoinFournitures = $besoinFournitures;

        return $this;
    }

    public function isBesoinInformatique(): ?bool
    {
        return $this->besoinInformatique;
    }

    public function setBesoinInformatique(?bool $besoinInformatique): static
    {
        $this->besoinInformatique = $besoinInformatique;

        return $this;
    }

    public function getCarteANTS(): ?string
    {
        return $this->carteANTS;
    }

    public function setCarteANTS(?string $carteANTS): static
    {
        $this->carteANTS = $carteANTS;

        return $this;
    }

    public function getCarteAchats(): ?string
    {
        return $this->carteAchats;
    }

    public function setCarteAchats(?string $carteAchats): static
    {
        $this->carteAchats = $carteAchats;

        return $this;
    }

    public function getChargeVoyages(): ?string
    {
        return $this->chargeVoyages;
    }

    public function setChargeVoyages(?string $chargeVoyages): static
    {
        $this->chargeVoyages = $chargeVoyages;

        return $this;
    }

    public function getCorrespondantBudgetaire(): ?string
    {
        return $this->correspondantBudgetaire;
    }

    public function setCorrespondantBudgetaire(?string $correspondantBudgetaire): static
    {
        $this->correspondantBudgetaire = $correspondantBudgetaire;

        return $this;
    }

    public function getEncadreAgents(): ?string
    {
        return $this->encadreAgents;
    }

    public function setEncadreAgents(?string $encadreAgents): static
    {
        $this->encadreAgents = $encadreAgents;

        return $this;
    }

    public function getUtiliseChorus(): ?string
    {
        return $this->utiliseChorus;
    }

    public function setUtiliseChorus(?string $utiliseChorus): static
    {
        $this->utiliseChorus = $utiliseChorus;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCommentaire(): ?string
    {
        return $this->commentaire;
    }

    public function setCommentaire(?string $commentaire): static
    {
        $this->commentaire = $commentaire;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): static
    {
        $this->createdBy = $createdBy;

        return $this;
    }

}
