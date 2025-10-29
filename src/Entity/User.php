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
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ServiceDependencies]
class User implements UserInterface, PasswordAuthenticatedUserInterface
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
    private ?DomaineService $domaine = null;

    #[ORM\ManyToOne]
    private ?Lieu $lieu = null;

    #[ORM\ManyToOne]
    private ?Categorie $categorie = null;

    #[Assert\NotBlank(groups: ['create'])]
    private ?string $plainPassword = null;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'createdUsers')]
    private ?self $createdBy = null;

    #[ORM\OneToMany(targetEntity: self::class, mappedBy: 'createdBy')]
    private Collection $createdUsers;

    #[ORM\OneToMany(targetEntity: DemandeHabilitationCerbere::class, mappedBy: 'validePar')]
    private Collection $demandeHabilitationCerberes;

    #[ORM\OneToMany(targetEntity: Notification::class, mappedBy: 'destinataire')]
    private Collection $notifications;

    public function __construct()
    {
        $this->createdUsers = new ArrayCollection();
        $this->demandeHabilitationCerberes = new ArrayCollection();
        $this->notifications = new ArrayCollection();
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

    public function getRoles(): array
    {
        $code = $this->role?->getCode();

        if (!$code || !str_starts_with($code, 'ROLE_')) {
            return ['ROLE_AGENT'];
        }

        // Hiérarchie des rôles selon security.yaml
        $roleHierarchy = [
            'ROLE_ADMIN' => ['ROLE_ADMIN', 'ROLE_USER', 'ROLE_AGENT'],
            'ROLE_CHEF' => ['ROLE_CHEF', 'ROLE_USER', 'ROLE_AGENT'],
            'ROLE_AGENT' => ['ROLE_AGENT', 'ROLE_USER'],
        ];

        return $roleHierarchy[$code] ?? [$code];
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

    public function getCategorie(): ?Categorie
    {
        return $this->categorie;
    }

    public function setCategorie(?Categorie $categorie): static
    {
        $this->categorie = $categorie;
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

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): static
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }

    public function getServiceDomaines(): string
    {
        return $this->service ? implode(', ', $this->service->getDomaines()->map(fn($d) => $d->getNom())->toArray()) : '';
    }

    public function getLieuxService(): string
    {
        return $this->service ? implode(', ', $this->service->getLieux()->map(fn($l) => $l->getNom())->toArray()) : '';
    }

    public function getUserIdentifier(): string
    {
        return $this->email ?? '';
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function __toString(): string
    {
        return $this->prenom . ' ' . $this->nom;
    }

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

    public function getDemandeHabilitationCerberes(): Collection
    {
        return $this->demandeHabilitationCerberes;
    }

    public function addDemandeHabilitationCerbere(DemandeHabilitationCerbere $demande): static
    {
        if (!$this->demandeHabilitationCerberes->contains($demande)) {
            $this->demandeHabilitationCerberes->add($demande);
            $demande->setValidePar($this);
        }
        return $this;
    }

    public function removeDemandeHabilitationCerbere(DemandeHabilitationCerbere $demande): static
    {
        if ($this->demandeHabilitationCerberes->removeElement($demande)) {
            if ($demande->getValidePar() === $this) {
                $demande->setValidePar(null);
            }
        }
        return $this;
    }
    public function getFullName(): string
    {
        return trim($this->nom . ' ' . $this->prenom);
    }

    /**
     * @return Collection<int, Notification>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notification $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setDestinataire($this);
        }

        return $this;
    }

    public function removeNotification(Notification $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            if ($notification->getDestinataire() === $this) {
                $notification->setDestinataire(null);
            }
        }

        return $this;
    }
}
