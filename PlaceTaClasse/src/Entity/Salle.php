<?php

namespace App\Entity;

use App\Repository\SalleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SalleRepository::class)]
class Salle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Plan;

    #[ORM\Column(type: 'boolean')]
    private $Amphi;

    #[ORM\Column(type: 'string', length: 255)]
    private $emplacementPrise;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom;

    #[ORM\Column(type: 'integer')]
    private $NbPlace;

    #[ORM\OneToMany(mappedBy: 'Salle', targetEntity: Place::class, orphanRemoval: true)]
    private $places;

    public function __construct()
    {
        $this->places = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlan(): ?string
    {
        return $this->Plan;
    }

    public function setPlan(string $Plan): self
    {
        $this->Plan = $Plan;

        return $this;
    }

    public function getAmphi(): ?bool
    {
        return $this->Amphi;
    }

    public function setAmphi(bool $Amphi): self
    {
        $this->Amphi = $Amphi;

        return $this;
    }

    public function getEmplacementPrise(): ?string
    {
        return $this->emplacementPrise;
    }

    public function setEmplacementPrise(string $emplacementPrise): self
    {
        $this->emplacementPrise = $emplacementPrise;

        return $this;
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

    public function getNbPlace(): ?int
    {
        return $this->NbPlace;
    }

    public function setNbPlace(int $NbPlace): self
    {
        $this->NbPlace = $NbPlace;

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Place $place): self
    {
        if (!$this->places->contains($place)) {
            $this->places[] = $place;
            $place->setSalle($this);
        }

        return $this;
    }

    public function removePlace(Place $place): self
    {
        if ($this->places->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getSalle() === $this) {
                $place->setSalle(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->getNom();
    }
}
