<?php

namespace App\Entity;

use App\Repository\PlanRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PlanRepository::class)]
class Plan
{
    public const PLAN_STATUS_ENATTENTE = 1;
    public const PLAN_STATUS_ENCOURS = 2;
    public const PLAN_STATUS_ACTIVE = 3;
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $chantier = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $notes = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 100)]
    private ?string $priorite = null;

    #[ORM\Column]
    private ?int $etat = null;

    #[ORM\ManyToOne(inversedBy: 'plans')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'plan', targetEntity: Fichiers::class,cascade:["persist"])]
    private Collection $fichiers;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\ManyToOne(inversedBy: 'decortiqueurs')]
    private ?User $decortiqueurs = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $noteDecortiqueur = null;

    #[ORM\OneToMany(mappedBy: 'plan', targetEntity: FichierDecor::class,cascade:["persist"])]
    private Collection $fiechierDeco;

    #[ORM\Column(nullable: true)]
    private ?float $tonage = 0;

    #[ORM\Column(nullable: true)]
    private ?float $tonnageTS = 0;

    #[ORM\Column(nullable: true)]
    private ?float $tonnageCF = 0;

    #[ORM\Column(nullable: true)]
    private ?float $tonnageCA = 0;

    /**
     * @ORM\PrePersist
     */
    public function setupdatedAtValue()
    {
        $this->createdAt = new \DateTime(); 
        $this->etat = self::PLAN_STATUS_ENATTENTE;
    }

    public function __construct()
    {
        $this->fichiers = new ArrayCollection();
        $this->etat = self::PLAN_STATUS_ENATTENTE;
        $this->fiechierDeco = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getChantier(): ?string
    {
        return $this->chantier;
    }

    public function setChantier(string $chantier): self
    {
        $this->chantier = $chantier;

        return $this;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(?string $notes): self
    {
        $this->notes = $notes;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getPriorite(): ?string
    {
        return $this->priorite;
    }

    public function setPriorite(string $priorite): self
    {
        $this->priorite = $priorite;

        return $this;
    }
    public function getEtat(): ?int
    {
        return $this->etat;
    }

    public function setEtat(int $etat): self
    {
        $this->etat = $etat;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Fichiers>
     */
    public function getFichiers(): Collection
    {
        return $this->fichiers;
    }

    public function addFichier(Fichiers $fichier): self
    {
        if (!$this->fichiers->contains($fichier)) {
            $this->fichiers->add($fichier);
            $fichier->setPlan($this);
        }

        return $this;
    }

    public function removeFichier(Fichiers $fichier): self
    {
        if ($this->fichiers->removeElement($fichier)) {
            // set the owning side to null (unless already changed)
            if ($fichier->getPlan() === $this) {
                $fichier->setPlan(null);
            }
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getDecortiqueurs(): ?User
    {
        return $this->decortiqueurs;
    }

    public function setDecortiqueurs(?User $decortiqueurs): self
    {
        $this->decortiqueurs = $decortiqueurs;

        return $this;
    }

    public function getNoteDecortiqueur(): ?string
    {
        return $this->noteDecortiqueur;
    }

    public function setNoteDecortiqueur(?string $noteDecortiqueur): self
    {
        $this->noteDecortiqueur = $noteDecortiqueur;

        return $this;
    }

    /**
     * @return Collection<int, FichierDecor>
     */
    public function getFiechierDeco(): Collection
    {
        return $this->fiechierDeco;
    }

    public function addFiechierDeco(FichierDecor $fiechierDeco): self
    {
        if (!$this->fiechierDeco->contains($fiechierDeco)) {
            $this->fiechierDeco->add($fiechierDeco);
            $fiechierDeco->setPlan($this);
        }

        return $this;
    }

    public function removeFiechierDeco(FichierDecor $fiechierDeco): self
    {
        if ($this->fiechierDeco->removeElement($fiechierDeco)) {
            // set the owning side to null (unless already changed)
            if ($fiechierDeco->getPlan() === $this) {
                $fiechierDeco->setPlan(null);
            }
        }

        return $this;
    }

    public function getTonage(): ?float
    {
        return $this->tonage;
    }

    public function setTonage(?float $tonage): self
    {
        $this->tonage = $tonage;

        return $this;
    }

    public function getTonnageTS(): ?float
    {
        return $this->tonnageTS;
    }

    public function setTonnageTS(?float $tonnageTS): self
    {
        $this->tonnageTS = $tonnageTS;

        return $this;
    }

    public function getTonnageCF(): ?float
    {
        return $this->tonnageCF;
    }

    public function setTonnageCF(?float $tonnageCF): self
    {
        $this->tonnageCF = $tonnageCF;

        return $this;
    }

    public function getTonnageCA(): ?float
    {
        return $this->tonnageCA;
    }

    public function setTonnageCA(float $tonnageCA): self
    {
        $this->tonnageCA = $tonnageCA;

        return $this;
    }

}
