<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column(type:"json")]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $entreprise = null;

    #[ORM\Column(length: 255)]
    private ?string $adresse = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Plan::class, orphanRemoval: true,cascade:["persist"])]
    private Collection $plans;

    #[ORM\OneToMany(mappedBy: 'decortiqueurs', targetEntity: Plan::class)]
    private Collection $decortiqueurs;

    #[ORM\OneToOne(mappedBy: 'user', targetEntity: Decortiqueur::class, cascade: ['persist', 'remove'])]
    private $decortiqueur;

    #[ORM\Column]
    private ?bool $isClient = null;

    public function __construct()
    {
        $this->plans = new ArrayCollection();
        $this->decortiqueurs = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getEntreprise(): ?string
    {
        return $this->entreprise;
    }

    public function setEntreprise(?string $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    // public function isVerified(): bool
    // {
    //     return $this->isVerified;
    // }

    public function getIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * @return Collection<int, Plan>
     */
    public function getPlans(): Collection
    {
        return $this->plans;
    }

    public function addPlan(Plan $plan): self
    {
        if (!$this->plans->contains($plan)) {
            $this->plans->add($plan);
            $plan->setUser($this);
        }

        return $this;
    }

    public function removePlan(Plan $plan): self
    {
        if ($this->plans->removeElement($plan)) {
            // set the owning side to null (unless already changed)
            if ($plan->getUser() === $this) {
                $plan->setUser(null);
            }
        }

        return $this;
    }
    public function __toString() {
        if ($this->entreprise ) {
            return $this->entreprise;
        }
        else {
            return $this->nom.' '.$this->prenom;
        }
    }

    /**
     * @return Collection<int, Plan>
     */
    public function getDecortiqueurs(): Collection
    {
        return $this->decortiqueurs;
    }

    public function addDecortiqueurs(Plan $decortiqueurs): self
    {
        if (!$this->decortiqueurs->contains($decortiqueurs)) {
            $this->decortiqueurs->add($decortiqueurs);
            $decortiqueurs->setDecortiqueurs($this);
        }

        return $this;
    }

    public function removeDecortiqueurs(Plan $decortiqueurs): self
    {
        if ($this->decortiqueurs->removeElement($decortiqueurs)) {
            // set the owning side to null (unless already changed)
            if ($decortiqueurs->getDecortiqueurs() === $this) {
                $decortiqueurs->setDecortiqueurs(null);
            }
        }

        return $this;
    }

    public function isIsClient(): ?bool
    {
        return $this->isClient;
    }

    public function setIsClient(bool $isClient): self
    {
        $this->isClient = $isClient;

        return $this;
    }

    public function getDecortiqueur(): ?Decortiqueur
    {
        return $this->decortiqueur;
    }

    public function setEditeur(?Decortiqueur $decortiqueur): self
    {
        // unset the owning side of the relation if necessary
        if ($decortiqueur === null && $this->decortiqueur !== null) {
            $this->decortiqueur->setUser(null);
        }

        // set the owning side of the relation if necessary
        if ($decortiqueur !== null && $decortiqueur->getUser() !== $this) {
            $decortiqueur->setUser($this);
        }

        $this->decortiqueur = $decortiqueur;

        return $this;
    }

}
