<?php

namespace App\Entity;

use App\Repository\EnseignantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EnseignantRepository::class)]
class Enseignant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\Column(type: 'string', length: 255)]
    private $Nom;

    #[ORM\Column(type: 'string', length: 255)]
    private $Prenom;

    #[ORM\Column(type: 'boolean')]
    private $Vacataire;

    #[ORM\Column(type: 'string', length: 255)]
    private $Mail;

    #[ORM\ManyToMany(targetEntity: Controle::class, mappedBy: 'Surveillant')]
    private $controlesSurveille;

    #[ORM\OneToMany(mappedBy: 'Referent', targetEntity: Controle::class)]
    private $controlesReferent;

    #[ORM\ManyToMany(targetEntity: Module::class, mappedBy: 'Enseignant')]
    private $modules;

    public function __construct()
    {
        $this->controlesSurveille = new ArrayCollection();
        $this->controlesReferent = new ArrayCollection();
        $this->modules = new ArrayCollection();
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

    public function getVacataire(): ?bool
    {
        return $this->Vacataire;
    }

    public function setVacataire(bool $Vacataire): self
    {
        $this->Vacataire = $Vacataire;

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
     * @return Collection<int, Controle>
     */
    public function getControlesSurveille(): Collection
    {
        return $this->controlesSurveille;
    }

    public function addControlesSurveille(Controle $controlesSurveille): self
    {
        if (!$this->controlesSurveille->contains($controlesSurveille)) {
            $this->controlesSurveille[] = $controlesSurveille;
            $controlesSurveille->addSurveillant($this);
        }

        return $this;
    }

    public function removeControlesSurveille(Controle $controlesSurveille): self
    {
        if ($this->controlesSurveille->removeElement($controlesSurveille)) {
            $controlesSurveille->removeSurveillant($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Controle>
     */
    public function getControlesReferent(): Collection
    {
        return $this->controlesReferent;
    }

    public function addControlesReferent(Controle $controlesReferent): self
    {
        if (!$this->controlesReferent->contains($controlesReferent)) {
            $this->controlesReferent[] = $controlesReferent;
            $controlesReferent->setReferent($this);
        }

        return $this;
    }

    public function removeControlesReferent(Controle $controlesReferent): self
    {
        if ($this->controlesReferent->removeElement($controlesReferent)) {
            // set the owning side to null (unless already changed)
            if ($controlesReferent->getReferent() === $this) {
                $controlesReferent->setReferent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Module>
     */
    public function getModules(): Collection
    {
        return $this->modules;
    }

    public function addModule(Module $module): self
    {
        if (!$this->modules->contains($module)) {
            $this->modules[] = $module;
            $module->addEnseignant($this);
        }

        return $this;
    }

    public function removeModule(Module $module): self
    {
        if ($this->modules->removeElement($module)) {
            $module->removeEnseignant($this);
        }

        return $this;
    }
}
