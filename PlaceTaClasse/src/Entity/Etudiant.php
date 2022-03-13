<?php

namespace App\Entity;

use App\Repository\EtudiantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EtudiantRepository::class)]
class Etudiant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $Prenom;

    #[ORM\Column(type: 'integer')]
    private $Tp;

    #[ORM\Column(type: 'boolean')]
    private $TierTemps;

    #[ORM\Column(type: 'boolean')]
    private $Ordinateur;

    #[ORM\Column(type: 'string', length: 255)]
    private $Mail;

    #[ORM\OneToMany(mappedBy: 'Placement', targetEntity: Placement::class)]
    private $placements;

    #[ORM\ManyToOne(targetEntity: Promotion::class, inversedBy: 'etudiants')]
    #[ORM\JoinColumn(nullable: false)]
    private $Promotion;

    public function __construct()
    {
        $this->placements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getTp(): ?int
    {
        return $this->Tp;
    }

    public function setTp(int $Tp): self
    {
        $this->Tp = $Tp;

        return $this;
    }

    public function getTierTemps(): ?bool
    {
        return $this->TierTemps;
    }

    public function setTierTemps(bool $TierTemps): self
    {
        $this->TierTemps = $TierTemps;

        return $this;
    }

    public function getOrdinateur(): ?bool
    {
        return $this->Ordinateur;
    }

    public function setOrdinateur(bool $Ordinateur): self
    {
        $this->Ordinateur = $Ordinateur;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->Mail;
    }

    public function setMail(string $Mail): self
    {
        $this->Mail = $Mail;

        return $this;
    }

    /**
     * @return Collection<int, Placement>
     */
    public function getPlacements(): Collection
    {
        return $this->placements;
    }

    public function addPlacement(Placement $placement): self
    {
        if (!$this->placements->contains($placement)) {
            $this->placements[] = $placement;
            $placement->setPlacer($this);
        }

        return $this;
    }

    public function removePlacement(Placement $placement): self
    {
        if ($this->placements->removeElement($placement)) {
            // set the owning side to null (unless already changed)
            if ($placement->getPlacer() === $this) {
                $placement->setPlacer(null);
            }
        }

        return $this;
    }

    public function getPromotion(): ?Promotion
    {
        return $this->Promotion;
    }

    public function setPromotion(?Promotion $Promotion): self
    {
        $this->Promotion = $Promotion;

        return $this;
    }
}
