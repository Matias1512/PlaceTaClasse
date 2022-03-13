<?php

namespace App\Entity;

use App\Repository\ModuleRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ModuleRepository::class)]
class Module
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $nomLong;

    #[ORM\Column(type: 'string', length: 255)]
    private $nomCourt;

    #[ORM\OneToMany(mappedBy: 'Module', targetEntity: Controle::class)]
    private $controles;

    #[ORM\ManyToMany(targetEntity: Enseignant::class, inversedBy: 'modules')]
    private $Enseignant;

    #[ORM\ManyToMany(targetEntity: Promotion::class, inversedBy: 'modules')]
    private $Promotion;

    public function __construct()
    {
        $this->controles = new ArrayCollection();
        $this->Enseignant = new ArrayCollection();
        $this->Promotion = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomLong(): ?string
    {
        return $this->nomLong;
    }

    public function setNomLong(string $nomLong): self
    {
        $this->nomLong = $nomLong;

        return $this;
    }

    public function getNomCourt(): ?string
    {
        return $this->nomCourt;
    }

    public function setNomCourt(string $nomCourt): self
    {
        $this->nomCourt = $nomCourt;

        return $this;
    }

    /**
     * @return Collection<int, Controle>
     */
    public function getControles(): Collection
    {
        return $this->controles;
    }

    public function addControle(Controle $controle): self
    {
        if (!$this->controles->contains($controle)) {
            $this->controles[] = $controle;
            $controle->setModule($this);
        }

        return $this;
    }

    public function removeControle(Controle $controle): self
    {
        if ($this->controles->removeElement($controle)) {
            // set the owning side to null (unless already changed)
            if ($controle->getModule() === $this) {
                $controle->setModule(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Enseignant>
     */
    public function getEnseignant(): Collection
    {
        return $this->Enseignant;
    }

    public function addEnseignant(Enseignant $enseignant): self
    {
        if (!$this->Enseignant->contains($enseignant)) {
            $this->Enseignant[] = $enseignant;
        }

        return $this;
    }

    public function removeEnseignant(Enseignant $enseignant): self
    {
        $this->Enseignant->removeElement($enseignant);

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
}
