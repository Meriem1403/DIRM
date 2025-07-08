<?php

namespace App\Entity;

use App\Repository\DeclarationChantierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use DateTimeInterface;
#[ORM\Entity(repositoryClass: DeclarationChantierRepository::class)]
class DeclarationChantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Exploitant
    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 50)]
    private ?string $qualiteDeclarant = null; // chantier, autre...

    // Déclaration
    #[ORM\Column(length: 50)]
    private ?string $typeDemande = null; // mise_en_chantier, modification...

    #[ORM\Column(length: 50)]
    private ?string $typeProjet = null; // commerce, pêche, plaisance pro...

    #[ORM\Column(type: Types::JSON)]
    private array $activites = []; // ex: transport, services, chargement

    // Navire
    #[ORM\Column(length: 150)]
    private ?string $nomNavire = null;

    #[ORM\Column(length: 150, nullable: true)]
    private ?string $pavillonOrigine = null;

    #[ORM\Column(length: 150)]
    private ?string $quartierImmatriculation = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $jauge = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $longueur = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $largeur = null;

    #[ORM\Column(length: 50)]
    private ?string $propulsion = null; // thermique, électrique...

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $puissanceKw = null;

    #[ORM\Column(length: 50)]
    private ?string $vitesse = null; // à 12 nds, 12-20, etc.

    #[ORM\Column(length: 100)]
    private ?string $materiau = null; // Acier, PRVT, etc.

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $datePoseQuille = null;

    #[ORM\Column(length: 255)]
    private ?string $chantierNaval = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $architecteNaval = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $organismeClasse = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $categorieConception = null; // A, B, C, D...

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroSerie = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroCoque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modulesEvaluation = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $organismeNotifie = null;

    // Personnes physiques déclarées
    #[ORM\Column(length: 100, nullable: true)]
    private ?string $autreNom = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $autrePrenom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $autreQualite = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $autreTelephone = null;

    // Suivi
    #[ORM\Column(length: 50)]
    private ?string $statut = null; // en_attente / validee / refusee

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $dateSoumission = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $dateValidation = null;

    #[ORM\ManyToOne]
    private ?User $validePar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recepissePath = null; // chemin vers PDF

    public function __toString(): string
    {
        return 'Déclaration de ' . $this->nom . ' - ' . $this->typeDemande;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;
        return $this;
    }

    public function getQualiteDeclarant(): ?string
    {
        return $this->qualiteDeclarant;
    }

    public function setQualiteDeclarant(?string $qualiteDeclarant): static
    {
        $this->qualiteDeclarant = $qualiteDeclarant;
        return $this;
    }

    public function getTypeDemande(): ?string
    {
        return $this->typeDemande;
    }

    public function setTypeDemande(?string $typeDemande): static
    {
        $this->typeDemande = $typeDemande;
        return $this;
    }

    public function getTypeProjet(): ?string
    {
        return $this->typeProjet;
    }

    public function setTypeProjet(?string $typeProjet): static
    {
        $this->typeProjet = $typeProjet;
        return $this;
    }

    public function getActivites(): ?array
    {
        return $this->activites;
    }

    public function setActivites(array $activites): static
    {
        $this->activites = $activites;
        return $this;
    }

    public function getNomNavire(): ?string
    {
        return $this->nomNavire;
    }

    public function setNomNavire(?string $nomNavire): static
    {
        $this->nomNavire = $nomNavire;
        return $this;
    }

    public function getPavillonOrigine(): ?string
    {
        return $this->pavillonOrigine;
    }

    public function setPavillonOrigine(?string $pavillonOrigine): static
    {
        $this->pavillonOrigine = $pavillonOrigine;
        return $this;
    }

    public function getQuartierImmatriculation(): ?string
    {
        return $this->quartierImmatriculation;
    }

    public function setQuartierImmatriculation(?string $quartierImmatriculation): static
    {
        $this->quartierImmatriculation = $quartierImmatriculation;
        return $this;
    }

    public function getJauge(): ?float
    {
        return $this->jauge;
    }

    public function setJauge(?float $jauge): static
    {
        $this->jauge = $jauge;
        return $this;
    }

    public function getLongueur(): ?float
    {
        return $this->longueur;
    }

    public function setLongueur(?float $longueur): static
    {
        $this->longueur = $longueur;
        return $this;
    }

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(?float $largeur): static
    {
        $this->largeur = $largeur;
        return $this;
    }

    public function getPropulsion(): ?string
    {
        return $this->propulsion;
    }

    public function setPropulsion(?string $propulsion): static
    {
        $this->propulsion = $propulsion;
        return $this;
    }

    public function getPuissanceKw(): ?float
    {
        return $this->puissanceKw;
    }

    public function setPuissanceKw(?float $puissanceKw): static
    {
        $this->puissanceKw = $puissanceKw;
        return $this;
    }

    public function getVitesse(): ?string
    {
        return $this->vitesse;
    }

    public function setVitesse(?string $vitesse): static
    {
        $this->vitesse = $vitesse;
        return $this;
    }

    public function getMateriau(): ?string
    {
        return $this->materiau;
    }

    public function setMateriau(?string $materiau): static
    {
        $this->materiau = $materiau;
        return $this;
    }

    public function getDatePoseQuille(): ?DateTimeInterface
    {
        return $this->datePoseQuille;
    }

    public function setDatePoseQuille(?DateTimeInterface $datePoseQuille): static
    {
        $this->datePoseQuille = $datePoseQuille;
        return $this;
    }

    public function getChantierNaval(): ?string
    {
        return $this->chantierNaval;
    }

    public function setChantierNaval(?string $chantierNaval): static
    {
        $this->chantierNaval = $chantierNaval;
        return $this;
    }

    public function getArchitecteNaval(): ?string
    {
        return $this->architecteNaval;
    }

    public function setArchitecteNaval(?string $architecteNaval): static
    {
        $this->architecteNaval = $architecteNaval;
        return $this;
    }

    public function getOrganismeClasse(): ?string
    {
        return $this->organismeClasse;
    }

    public function setOrganismeClasse(?string $organismeClasse): static
    {
        $this->organismeClasse = $organismeClasse;
        return $this;
    }

    public function getCategorieConception(): ?string
    {
        return $this->categorieConception;
    }

    public function setCategorieConception(?string $categorieConception): static
    {
        $this->categorieConception = $categorieConception;
        return $this;
    }

    public function getNumeroSerie(): ?string
    {
        return $this->numeroSerie;
    }

    public function setNumeroSerie(?string $numeroSerie): static
    {
        $this->numeroSerie = $numeroSerie;
        return $this;
    }

    public function getNumeroCoque(): ?string
    {
        return $this->numeroCoque;
    }

    public function setNumeroCoque(?string $numeroCoque): static
    {
        $this->numeroCoque = $numeroCoque;
        return $this;
    }

    public function getModulesEvaluation(): ?string
    {
        return $this->modulesEvaluation;
    }

    public function setModulesEvaluation(?string $modulesEvaluation): static
    {
        $this->modulesEvaluation = $modulesEvaluation;
        return $this;
    }

    public function getOrganismeNotifie(): ?string
    {
        return $this->organismeNotifie;
    }

    public function setOrganismeNotifie(?string $organismeNotifie): static
    {
        $this->organismeNotifie = $organismeNotifie;
        return $this;
    }

    public function getAutreNom(): ?string
    {
        return $this->autreNom;
    }

    public function setAutreNom(?string $autreNom): static
    {
        $this->autreNom = $autreNom;
        return $this;
    }

    public function getAutrePrenom(): ?string
    {
        return $this->autrePrenom;
    }

    public function setAutrePrenom(?string $autrePrenom): static
    {
        $this->autrePrenom = $autrePrenom;
        return $this;
    }

    public function getAutreQualite(): ?string
    {
        return $this->autreQualite;
    }

    public function setAutreQualite(?string $autreQualite): static
    {
        $this->autreQualite = $autreQualite;
        return $this;
    }

    public function getAutreTelephone(): ?string
    {
        return $this->autreTelephone;
    }

    public function setAutreTelephone(?string $autreTelephone): static
    {
        $this->autreTelephone = $autreTelephone;
        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(?string $statut): static
    {
        $this->statut = $statut;
        return $this;
    }

    public function getDateSoumission(): ?DateTimeImmutable
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(?DateTimeImmutable $dateSoumission): static
    {
        $this->dateSoumission = $dateSoumission;
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

    public function getValidePar(): ?User
    {
        return $this->validePar;
    }

    public function setValidePar(?User $validePar): static
    {
        $this->validePar = $validePar;
        return $this;
    }

    public function getRecepissePath(): ?string
    {
        return $this->recepissePath;
    }

    public function setRecepissePath(?string $recepissePath): static
    {
        $this->recepissePath = $recepissePath;
        return $this;
    }

}
