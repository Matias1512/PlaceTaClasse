<?php

namespace App\Entity;

use App\Repository\ControleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ControleRepository::class)]
class Controle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $HoraireTTDebut;

    #[ORM\Column(type: 'string', length: 255)]
    private $HoraireTTFin;

    #[ORM\Column(type: 'string', length: 255)]
    private $HoraireNonTTDebut;

    #[ORM\Column(type: 'string', length: 255)]
    private $HoraireNonTTFin;

    #[ORM\ManyToMany(targetEntity: Placement::class, inversedBy: 'controles')]
    private $Placement;

    #[ORM\ManyToMany(targetEntity: Enseignant::class, inversedBy: 'controlesSurveille')]
    private $Surveillant;

    #[ORM\ManyToOne(targetEntity: Enseignant::class, inversedBy: 'controlesReferent')]
    private $Referent;

    #[ORM\ManyToOne(targetEntity: Module::class, inversedBy: 'controles')]
    private $Module;

    #[ORM\ManyToMany(targetEntity: Promotion::class, inversedBy: 'controles')]
    private $Promotion;

    #[ORM\Column(type: 'date')]
    private $Date;

    public function __construct()
    {
        $this->Placement = new ArrayCollection();
        $this->Surveillant = new ArrayCollection();
        $this->Promotion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHoraireTTDebut(): ?string
    {
        return $this->HoraireTTDebut;
    }

    public function setHoraireTTDebut(string $HoraireTTDebut): self
    {
        $this->HoraireTTDebut = $HoraireTTDebut;

        return $this;
    }

    public function getHoraireTTFin(): ?string
    {
        return $this->HoraireTTFin;
    }

    public function setHoraireTTFin(string $HoraireTTFin): self
    {
        $this->HoraireTTFin = $HoraireTTFin;

        return $this;
    }

    public function getHoraireNonTTDebut(): ?string
    {
        return $this->HoraireNonTTDebut;
    }

    public function setHoraireNonTTDebut(string $HoraireNonTTDebut): self
    {
        $this->HoraireNonTTDebut = $HoraireNonTTDebut;

        return $this;
    }

    public function getHoraireNonTTFin(): ?string
    {
        return $this->HoraireNonTTFin;
    }

    public function setHoraireNonTTFin(string $HoraireNonTTFin): self
    {
        $this->HoraireNonTTFin = $HoraireNonTTFin;

        return $this;
    }

    /**
     * @return Collection<int, Placement>
     */
    public function getPlacement(): Collection
    {
        return $this->Placement;
    }

    public function addPlacement(Placement $placement): self
    {
        if (!$this->Placement->contains($placement)) {
            $this->Placement[] = $placement;
        }

        return $this;
    }

    public function removePlacement(Placement $placement): self
    {
        $this->Placement->removeElement($placement);

        return $this;
    }

    /**
     * @return Collection<int, Enseignant>
     */
    public function getSurveillant(): Collection
    {
        return $this->Surveillant;
    }

    public function addSurveillant(Enseignant $surveillant): self
    {
        if (!$this->Surveillant->contains($surveillant)) {
            $this->Surveillant[] = $surveillant;
        }

        return $this;
    }

    public function removeSurveillant(Enseignant $surveillant): self
    {
        $this->Surveillant->removeElement($surveillant);

        return $this;
    }

    public function getReferent(): ?Enseignant
    {
        return $this->Referent;
    }

    public function setReferent(?Enseignant $Referent): self
    {
        $this->Referent = $Referent;

        return $this;
    }

    public function getModule(): ?Module
    {
        return $this->Module;
    }

    public function setModule(?Module $Module): self
    {
        $this->Module = $Module;

        return $this;
    }

    /**
     * @return Collection<int, Promotion>
     */
    public function getPromotion(): Collection
    {
        return $this->Promotion;
    }

    public function addPromotion(Promotion $promotion): self
    {
        if (!$this->Promotion->contains($promotion)) {
            $this->Promotion[] = $promotion;
        }

        return $this;
    }

    public function removePromotion(Promotion $promotion): self
    {
        $this->Promotion->removeElement($promotion);

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->Date;
    }

    public function setDate(\DateTimeInterface $Date): self
    {
        $this->Date = $Date;

        return $this;
    }

    public function __toString()
    {
        return $this->getId();
    }
}
