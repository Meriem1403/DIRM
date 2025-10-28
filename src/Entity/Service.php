<?php

namespace App\Entity;

use App\Repository\ServiceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ServiceRepository::class)]
class Service
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column]
    private ?bool $actif = true;

    #[ORM\ManyToMany(targetEntity: DomaineService::class, mappedBy: 'services')]
    private Collection $domaines;

    #[ORM\ManyToMany(targetEntity: Lieu::class, mappedBy: 'services')]
    private Collection $lieux;

    /**
     * @var Collection<int, User>
     */
    #[ORM\OneToMany(targetEntity: User::class, mappedBy: 'service', cascade: ['remove'])]
    private Collection $users;

    /**
     * @var Collection<int, Goudurix>
     */
    #[ORM\OneToMany(targetEntity: Goudurix::class, mappedBy: 'service', cascade: ['remove'])]
    private Collection $risques;

    public function __construct()
    {
        $this->domaines = new ArrayCollection();
        $this->lieux = new ArrayCollection();
        $this->users = new ArrayCollection();
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
     * @return Collection<int, DomaineService>
     */
    public function getDomaines(): Collection
    {
        return $this->domaines;
    }

    public function addDomaine(DomaineService $domaine): static
    {
        if (!$this->domaines->contains($domaine)) {
            $this->domaines->add($domaine);
            $domaine->addService($this);
        }

        return $this;
    }

    public function removeDomaine(DomaineService $domaine): static
    {
        if ($this->domaines->removeElement($domaine)) {
            $domaine->removeService($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Lieu>
     */
    public function getLieux(): Collection
    {
        return $this->lieux;
    }

    public function addLieu(Lieu $lieu): static
    {
        if (!$this->lieux->contains($lieu)) {
            $this->lieux->add($lieu);
            $lieu->addService($this);
        }

        return $this;
    }

    public function removeLieu(Lieu $lieu): static
    {
        if ($this->lieux->removeElement($lieu)) {
            $lieu->removeService($this);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->nom ?? 'Service';
    }

    public function addLieux(Lieu $lieux): static
    {
        if (!$this->lieux->contains($lieux)) {
            $this->lieux->add($lieux);
            $lieux->addService($this);
        }

        return $this;
    }

    public function removeLieux(Lieu $lieux): static
    {
        if ($this->lieux->removeElement($lieux)) {
            $lieux->removeService($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->setService($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            // set the owning side to null (unless already changed)
            if ($user->getService() === $this) {
                $user->setService(null);
            }
        }

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
            $risque->setService($this);
        }

        return $this;
    }

    public function removeRisque(Goudurix $risque): static
    {
        if ($this->risques->removeElement($risque)) {
            // set the owning side to null (unless already changed)
            if ($risque->getService() === $this) {
                $risque->setService(null);
            }
        }

        return $this;
    }
}
