<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use DateTimeImmutable;
use DateTime;
use App\Validator\ServiceDependencies;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ServiceDependencies]
class User implements PasswordAuthenticatedUserInterface

{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $nom = null;

    #[ORM\Column(length: 100)]
    private ?string $prenom = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?DateTime $dateNaissance = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(length: 100)]
    private ?string $ville = null;

    #[ORM\Column(length: 10)]
    private ?string $codePostal = null;

    #[ORM\Column(length: 100)]
    private ?string $pays = null;

    #[ORM\Column(length: 150)]
    private ?string $poste = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Role $role = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Service $service = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?DomaineService $domaine = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: true)]
    private ?Categorie $categorie = null;

    #[Assert\NotBlank(groups: ['create'])]
    private ?string $plainPassword = null;

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }


    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'createdUsers')]
    #[ORM\JoinColumn(nullable: true)]
    private ?self $createdBy = null;

    /**
     * @var Collection<int, self>
     */
    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'createdBy')]
    private Collection $createdUsers;

    /**
     * @var Collection<int, DemandeHabilitationCerbere>
     */
    #[ORM\OneToMany(targetEntity: DemandeHabilitationCerbere::class, mappedBy: 'validePar')]
    private Collection $demandeHabilitationCerberes;

    public function __construct()
    {
        $this->createdUsers = new ArrayCollection();
        $this->demandeHabilitationCerberes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
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

    public function getDomaine(): ?DomaineService
    {
        return $this->domaine;
    }

    public function setDomaine(?DomaineService $domaine): static
    {
        $this->domaine = $domaine;
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


    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;
        return $this;
    }

    public function getDateNaissance(): ?DateTime
    {
        return $this->dateNaissance;
    }

    public function setDateNaissance(DateTime $dateNaissance): static
    {
        $this->dateNaissance = $dateNaissance;
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

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): static
    {
        $this->ville = $ville;
        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function setCodePostal(string $codePostal): static
    {
        $this->codePostal = $codePostal;
        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(string $pays): static
    {
        $this->pays = $pays;
        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): static
    {
        $this->poste = $poste;
        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;
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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getCreatedBy(): ?self
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?self $createdBy): static
    {
        $this->createdBy = $createdBy;
        return $this;
    }

    /**
     * @return Collection<int, self>
     */
    public function getCreatedUsers(): Collection
    {
        return $this->createdUsers;
    }

    public function addCreatedUser(self $user): static
    {
        if (!$this->createdUsers->contains($user)) {
            $this->createdUsers->add($user);
            $user->setCreatedBy($this);
        }

        return $this;
    }

    public function removeCreatedUser(self $user): static
    {
        if ($this->createdUsers->removeElement($user)) {
            if ($user->getCreatedBy() === $this) {
                $user->setCreatedBy(null);
            }
        }

        return $this;
    }
    public function getServiceDomaines(): string
    {
        if (!$this->service) return '';

        return implode(', ', $this->service->getDomaines()->map(fn($d) => $d->getNom())->toArray());
    }


    public function getLieuxService(): string
    {
        if (!$this->service) {
            return '';
        }

        return implode(', ', $this->service->getLieux()->map(fn($l) => $l->getNom())->toArray());
    }


    public function getRoles(): array
    {
        return [$this->role?->getCode() ?? 'ROLE_AGENT'];
    }

    public function __toString(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

    /**
     * @return Collection<int, DemandeHabilitationCerbere>
     */
    public function getDemandeHabilitationCerberes(): Collection
    {
        return $this->demandeHabilitationCerberes;
    }

    public function addDemandeHabilitationCerbere(DemandeHabilitationCerbere $demandeHabilitationCerbere): static
    {
        if (!$this->demandeHabilitationCerberes->contains($demandeHabilitationCerbere)) {
            $this->demandeHabilitationCerberes->add($demandeHabilitationCerbere);
            $demandeHabilitationCerbere->setValidePar($this);
        }

        return $this;
    }

    public function removeDemandeHabilitationCerbere(DemandeHabilitationCerbere $demandeHabilitationCerbere): static
    {
        if ($this->demandeHabilitationCerberes->removeElement($demandeHabilitationCerbere)) {
            // set the owning side to null (unless already changed)
            if ($demandeHabilitationCerbere->getValidePar() === $this) {
                $demandeHabilitationCerbere->setValidePar(null);
            }
        }

        return $this;
    }
}
