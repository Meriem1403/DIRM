<?php

namespace App\Entity;

use App\Repository\SuiviEmploiRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SuiviEmploiRepository::class)]
#[ORM\Table(name: 'suivi_emploi')]
class SuiviEmploi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(name: 'nir', type: Types::STRING, length: 50, nullable: true)]
    private ?string $nir = null;

    #[ORM\Column(name: 'matricule_sirh', type: Types::STRING, length: 50, nullable: true)]
    private ?string $matriculeSirh = null;

    #[ORM\Column(name: 'nom_usage', type: Types::STRING, length: 255, nullable: true)]
    private ?string $nomUsage = null;

    #[ORM\Column(name: 'nom_naissance', type: Types::STRING, length: 255, nullable: true)]
    private ?string $nomNaissance = null;

    #[ORM\Column(name: 'prenom', type: Types::STRING, length: 255, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column(name: 'niveau06_libelle_court', type: Types::STRING, length: 255, nullable: true)]
    private ?string $niveau06LibelleCourt = null;

    #[ORM\Column(name: 'niveau08_libelle_court', type: Types::STRING, length: 255, nullable: true)]
    private ?string $niveau08LibelleCourt = null;

    #[ORM\Column(name: 'poste_code', type: Types::STRING, length: 50, nullable: true)]
    private ?string $posteCode = null;

    #[ORM\Column(name: 'poste_libelle_long', type: Types::TEXT, nullable: true)]
    private ?string $posteLibelleLong = null;

    #[ORM\Column(name: 'etpt_rh', type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $etptRh = null;

    #[ORM\Column(name: 'etpt_prog_action', type: Types::DECIMAL, precision: 10, scale: 2, nullable: true)]
    private ?string $etptProgAction = null;

    #[ORM\Column(name: 'code_nne_date_obs', type: Types::BIGINT, nullable: true)]
    private ?string $codeNneDateObs = null;

    #[ORM\Column(name: 'nne_libelle_date_obs', type: Types::STRING, length: 255, nullable: true)]
    private ?string $nneLibelleDateObs = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNir(): ?string
    {
        return $this->nir;
    }

    public function setNir(?string $nir): static
    {
        $this->nir = $nir;
        return $this;
    }

    public function getMatriculeSirh(): ?string
    {
        return $this->matriculeSirh;
    }

    public function setMatriculeSirh(?string $matriculeSirh): static
    {
        $this->matriculeSirh = $matriculeSirh;
        return $this;
    }

    public function getNomUsage(): ?string
    {
        return $this->nomUsage;
    }

    public function setNomUsage(?string $nomUsage): static
    {
        $this->nomUsage = $nomUsage;
        return $this;
    }

    public function getNomNaissance(): ?string
    {
        return $this->nomNaissance;
    }

    public function setNomNaissance(?string $nomNaissance): static
    {
        $this->nomNaissance = $nomNaissance;
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

    public function getNiveau06LibelleCourt(): ?string
    {
        return $this->niveau06LibelleCourt;
    }

    public function setNiveau06LibelleCourt(?string $niveau06LibelleCourt): static
    {
        $this->niveau06LibelleCourt = $niveau06LibelleCourt;
        return $this;
    }

    public function getNiveau08LibelleCourt(): ?string
    {
        return $this->niveau08LibelleCourt;
    }

    public function setNiveau08LibelleCourt(?string $niveau08LibelleCourt): static
    {
        $this->niveau08LibelleCourt = $niveau08LibelleCourt;
        return $this;
    }

    public function getPosteCode(): ?string
    {
        return $this->posteCode;
    }

    public function setPosteCode(?string $posteCode): static
    {
        $this->posteCode = $posteCode;
        return $this;
    }

    public function getPosteLibelleLong(): ?string
    {
        return $this->posteLibelleLong;
    }

    public function setPosteLibelleLong(?string $posteLibelleLong): static
    {
        $this->posteLibelleLong = $posteLibelleLong;
        return $this;
    }

    public function getEtptRh(): ?string
    {
        return $this->etptRh;
    }

    public function setEtptRh(?string $etptRh): static
    {
        $this->etptRh = $etptRh;
        return $this;
    }

    public function getEtptProgAction(): ?string
    {
        return $this->etptProgAction;
    }

    public function setEtptProgAction(?string $etptProgAction): static
    {
        $this->etptProgAction = $etptProgAction;
        return $this;
    }

    public function getCodeNneDateObs(): ?string
    {
        return $this->codeNneDateObs;
    }

    public function setCodeNneDateObs(?string $codeNneDateObs): static
    {
        $this->codeNneDateObs = $codeNneDateObs;
        return $this;
    }

    public function getNneLibelleDateObs(): ?string
    {
        return $this->nneLibelleDateObs;
    }

    public function setNneLibelleDateObs(?string $nneLibelleDateObs): static
    {
        $this->nneLibelleDateObs = $nneLibelleDateObs;
        return $this;
    }
}
