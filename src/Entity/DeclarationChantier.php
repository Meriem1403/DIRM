<?php

namespace App\Entity;

use App\Repository\DeclarationChantierRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use DateTimeInterface;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

#[ORM\Entity(repositoryClass: DeclarationChantierRepository::class)]
class DeclarationChantier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100)]
    private ?string $email = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $emailOrganisme = null;

    #[ORM\Column(length: 20)]
    private ?string $telephone = null;

    #[ORM\Column(length: 50)]
    private ?string $typeDemande = null;

    #[ORM\Column(type: Types::JSON)]
    private array $activites = [];

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
    private ?string $propulsion = null;

    #[ORM\Column(type: Types::FLOAT)]
    private ?float $puissanceKw = null;

    #[ORM\Column(length: 50)]
    private ?string $vitesse = null;

    #[ORM\Column(length: 100)]
    private ?string $materiau = null;

    #[ORM\Column(length: 100)]
    private ?string $eloignementCote = null;

    #[ORM\Column(length: 100)]
    private ?string $dureeSejourMer = null;


    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTimeInterface $datePoseQuille = null;


    #[ORM\Column(length: 255, nullable: true)]
    private ?string $architecteNaval = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $organismeClasse = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $categorieConception = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroSerie = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroCoque = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $modulesEvaluation = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $organismeNotifie = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomChantier = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomChantierConception = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $autrePrenom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $autreQualite = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $autreTelephone = null;

    #[ORM\Column(nullable: true)]
    private ?bool $chantierAvecContrat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $adresseChantier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactChantier = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $architecteMail = null;
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactArchitecte = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $contactOrganismeClasse = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $numeroExamenCe = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomMandataire = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenomMandataire = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $qualiteMandataire = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $telephoneMandataire = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $emailMandataire = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nomMandataireIa = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $prenomMandataireIa = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $qualiteMandataireIa = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $telephoneMandataireIa = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $emailMandataireIa = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $portDepart = null;

    #[ORM\Column(length: 50)]
    private ?string $statut = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private ?DateTimeImmutable $dateSoumission = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private ?DateTimeImmutable $dateValidation = null;

    #[ORM\ManyToOne]
    private ?User $validePar = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $recepissePath = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $noteExplicativePath = null;


    #[ORM\OneToMany(targetEntity: PersonneABord::class, mappedBy: 'declarationChantier', cascade: ['persist'], orphanRemoval: true)]
    private Collection $personnesABord;

    #[ORM\Column(nullable: true)]
    private ?int $nbEquipage = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbPassagers = null;

    #[ORM\Column(nullable: true)]
    private ?int $nbPersonnelSpecial = null;


    public function __construct()
    {
        $this->personnesABord = new ArrayCollection();
    }

    public function __toString(): string
    {
        return 'Déclaration de ' . $this->nom . ' - ' . $this->typeDemande;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomChantier(): ?string
    {
        return $this->nomChantier;
    }

    public function setNomChantier(?string $nomChantier): static
    {
        $this->nomChantier = $nomChantier;

        return $this;
    }

    public function getNomChantierConception(): ?string
    {
        return $this->nomChantierConception;
    }

    public function setNomChantierConception(?string $nomChantierConception): static
    {
        $this->nomChantierConception = $nomChantierConception;

        return $this;
    }

    public function getNbEquipage(): ?int
    {
        return $this->nbEquipage;
    }

    public function setNbEquipage(?int $nbEquipage): static
    {
        $this->nbEquipage = $nbEquipage;
        return $this;
    }

    public function getNbPersonnelSpecial(): ?int
    {
        return $this->nbPersonnelSpecial;
    }

    public function setNbPersonnelSpecial(?int $nbPersonnelSpecial): static
    {
        $this->nbPersonnelSpecial = $nbPersonnelSpecial;
        return $this;
    }

    public function getNbPassagers(): ?int
    {
        return $this->nbPassagers;
    }

    public function setNbPassagers(?int $nbPassagers): static
    {
        $this->nbPassagers = $nbPassagers;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;
        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): static
    {
        $this->adresse = $adresse;
        return $this;
    }

    public function getPortDepart(): ?string
    {
        return $this->portDepart;
    }

    public function setPortDepart(?string $portDepart): static
    {
        $this->portDepart = $portDepart;
        return $this;
    }



    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getEmailOrganisme(): ?string
    {
        return $this->emailOrganisme;
    }

    public function setEmailOrganisme(string $emailOrganisme): static
    {
        $this->emailOrganisme = $emailOrganisme;
        return $this;
    }


    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): static
    {
        $this->telephone = $telephone;
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

    public function getActivites(): array
    {
        return $this->activites;
    }

    public function setActivites(array $activites): static
    {
        $this->activites = $activites;
        return $this;
    }

    /**
     * @return Collection<int, PersonneABord>
     */
    public function getPersonnesABord(): Collection
    {
        return $this->personnesABord;
    }

    public function addPersonneAbord(PersonneABord $personne): static
    {
        if (!$this->personnesABord->contains($personne)) {
            $this->personnesABord[] = $personne;
            $personne->setDeclarationChantier($this);
        }
        return $this;
    }

    public function removePersonneAbord(PersonneABord $personne): static
    {
        if ($this->personnesABord->removeElement($personne)) {
            if ($personne->getDeclarationChantier() === $this) {
                $personne->setDeclarationChantier(null);
            }
        }
        return $this;
    }

    public function getNomNavire(): ?string
    {
        return $this->nomNavire;
    }

    public function setNomNavire(string $nomNavire): static
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

    public function setQuartierImmatriculation(string $quartierImmatriculation): static
    {
        $this->quartierImmatriculation = $quartierImmatriculation;
        return $this;
    }

    public function getJauge(): ?float
    {
        return $this->jauge;
    }

    public function setJauge(float $jauge): static
    {
        $this->jauge = $jauge;
        return $this;
    }

    public function getLongueur(): ?float
    {
        return $this->longueur;
    }

    public function setLongueur(float $longueur): static
    {
        $this->longueur = $longueur;
        return $this;
    }

    public function getLargeur(): ?float
    {
        return $this->largeur;
    }

    public function setLargeur(float $largeur): static
    {
        $this->largeur = $largeur;
        return $this;
    }

    public function getPropulsion(): ?string
    {
        return $this->propulsion;
    }

    public function setPropulsion(string $propulsion): static
    {
        $this->propulsion = $propulsion;
        return $this;
    }

    public function getPuissanceKw(): ?float
    {
        return $this->puissanceKw;
    }

    public function setPuissanceKw(float $puissanceKw): static
    {
        $this->puissanceKw = $puissanceKw;
        return $this;
    }

    public function getVitesse(): ?string
    {
        return $this->vitesse;
    }

    public function setVitesse(string $vitesse): static
    {
        $this->vitesse = $vitesse;
        return $this;
    }

    public function getMateriau(): ?string
    {
        return $this->materiau;
    }
    public function setMateriau(string $materiau): static
    {
        $this->materiau = $materiau;
        return $this;
    }

    public function getEloignementCote(): ?string
    {
        return $this->eloignementCote;
    }

    public function setEloignementCote(string $eloignementCote): static
    {
        $this->eloignementCote = $eloignementCote;
        return $this;
    }







    public function getDatePoseQuille(): ?DateTimeInterface
    {
        return $this->datePoseQuille;
    }

    public function setDatePoseQuille(DateTime $datePoseQuille): static
    {
        $this->datePoseQuille = $datePoseQuille;
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

    public function getArchitecteMail(): ?string
    {
        return $this->architecteMail;
    }

    public function setArchitecteMail(?string $architecteMail): static
    {
        $this->architecteMail = $architecteMail;
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

    public function isChantierAvecContrat(): ?bool
    {
        return $this->chantierAvecContrat;
    }

    public function setChantierAvecContrat(?bool $chantierAvecContrat): static
    {
        $this->chantierAvecContrat = $chantierAvecContrat;

        return $this;
    }

    public function getAdresseChantier(): ?string
    {
        return $this->adresseChantier;
    }

    public function setAdresseChantier(?string $adresseChantier): static
    {
        $this->adresseChantier = $adresseChantier;

        return $this;
    }

    public function getContactChantier(): ?string
    {
        return $this->contactChantier;
    }

    public function setContactChantier(?string $contactChantier): static
    {
        $this->contactChantier = $contactChantier;

        return $this;
    }

    public function getContactArchitecte(): ?string
    {
        return $this->contactArchitecte;
    }

    public function setContactArchitecte(?string $contactArchitecte): static
    {
        $this->contactArchitecte = $contactArchitecte;

        return $this;
    }

    public function getContactOrganismeClasse(): ?string
    {
        return $this->contactOrganismeClasse;
    }

    public function setContactOrganismeClasse(?string $contactOrganismeClasse): static
    {
        $this->contactOrganismeClasse = $contactOrganismeClasse;

        return $this;
    }

    public function getNumeroExamenCe(): ?string
    {
        return $this->numeroExamenCe;
    }

    public function setNumeroExamenCe(?string $numeroExamenCe): static
    {
        $this->numeroExamenCe = $numeroExamenCe;

        return $this;
    }

    public function getNomMandataire(): ?string
    {
        return $this->nomMandataire;
    }

    public function setNomMandataire(?string $nomMandataire): static
    {
        $this->nomMandataire = $nomMandataire;

        return $this;
    }

    public function getPrenomMandataire(): ?string
    {
        return $this->prenomMandataire;
    }

    public function setPrenomMandataire(?string $prenomMandataire): static
    {
        $this->prenomMandataire = $prenomMandataire;

        return $this;
    }

    public function getQualiteMandataire(): ?string
    {
        return $this->qualiteMandataire;
    }

    public function setQualiteMandataire(?string $qualiteMandataire): static
    {
        $this->qualiteMandataire = $qualiteMandataire;

        return $this;
    }

    public function getTelephoneMandataire(): ?string
    {
        return $this->telephoneMandataire;
    }

    public function setTelephoneMandataire(?string $telephoneMandataire): static
    {
        $this->telephoneMandataire = $telephoneMandataire;

        return $this;
    }

    public function getDureeSejourMer(): ?string
    {
        return $this->dureeSejourMer;
    }

    public function setDureeSejourMer(?string $dureeSejourMer): static
    {
        $this->dureeSejourMer = $dureeSejourMer;

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

    public function getDateSoumission(): ?\DateTimeImmutable
    {
        return $this->dateSoumission;
    }

    public function setDateSoumission(\DateTimeImmutable $dateSoumission): static
    {
        $this->dateSoumission = $dateSoumission;

        return $this;
    }

    public function getDateValidation(): ?\DateTimeImmutable
    {
        return $this->dateValidation;
    }

    public function setDateValidation(?\DateTimeImmutable $dateValidation): static
    {
        $this->dateValidation = $dateValidation;

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

    public function getNoteExplicativePath(): ?string
    {
        return $this->noteExplicativePath;
    }

    public function setNoteExplicativePath(?string $noteExplicativePath): static
    {
        $this->noteExplicativePath = $noteExplicativePath;

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
    public function getEmailMandataire(): ?string
    {
        return $this->emailMandataire;
    }

    public function setEmailMandataire(?string $emailMandataire): static
    {
        $this->emailMandataire = $emailMandataire;
        return $this;
    }

    public function getNomMandataireIa(): ?string
    {
        return $this->nomMandataireIa;
    }

    public function setNomMandataireIa(?string $nomMandataireIa): static
    {
        $this->nomMandataireIa = $nomMandataireIa;
        return $this;
    }

    public function getPrenomMandataireIa(): ?string
    {
        return $this->prenomMandataireIa;
    }

    public function setPrenomMandataireIa(?string $prenomMandataireIa): static
    {
        $this->prenomMandataireIa = $prenomMandataireIa;
        return $this;
    }

    public function getQualiteMandataireIa(): ?string
    {
        return $this->qualiteMandataireIa;
    }

    public function setQualiteMandataireIa(?string $qualiteMandataireIa): static
    {
        $this->qualiteMandataireIa = $qualiteMandataireIa;
        return $this;
    }

    public function getTelephoneMandataireIa(): ?string
    {
        return $this->telephoneMandataireIa;
    }

    public function setTelephoneMandataireIa(?string $telephoneMandataireIa): static
    {
        $this->telephoneMandataireIa = $telephoneMandataireIa;
        return $this;
    }

    public function getEmailMandataireIa(): ?string
    {
        return $this->emailMandataireIa;
    }

    public function setEmailMandataireIa(?string $emailMandataireIa): static
    {
        $this->emailMandataireIa = $emailMandataireIa;
        return $this;
    }


    public function addPersonnesABord(PersonneABord $personnesABord): static
    {
        if (!$this->personnesABord->contains($personnesABord)) {
            $this->personnesABord->add($personnesABord);
            $personnesABord->setDeclarationChantier($this);
        }

        return $this;
    }

    public function removePersonnesABord(PersonneABord $personnesABord): static
    {
        if ($this->personnesABord->removeElement($personnesABord)) {
            // set the owning side to null (unless already changed)
            if ($personnesABord->getDeclarationChantier() === $this) {
                $personnesABord->setDeclarationChantier(null);
            }
        }

        return $this;
    }
}
