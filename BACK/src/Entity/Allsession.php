<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\AllsessionRepository")
 */
class Allsession
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grow","note"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow","note"})
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $statut;

    /**
     * @ORM\Column(type="json",nullable=true)
     * @Groups({"grow"})
     */
    private $teams = [];

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Structure", inversedBy="allsessions")
     */
    private $structure;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $concerner;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluation", mappedBy="session")
     */
    private $evaluations;


    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Historiquesession", mappedBy="session")
     */
    private $historiquesessions;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $annee;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mois;

    public function __construct()
    {
        $this->evaluations = new ArrayCollection();
        $this->historiquesessions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?string
    {
        return $this->date;
    }

    public function setDate(string $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTeams(): ?array
    {
        return $this->teams;
    }

    public function setTeams(array $teams): self
    {
        $this->teams = $teams;

        return $this;
    }

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    public function getConcerner(): ?string
    {
        return $this->concerner;
    }

    public function setConcerner(string $concerner): self
    {
        $this->concerner = $concerner;

        return $this;
    }

    /**
     * @return Collection|Evaluation[]
     */
    public function getEvaluations(): Collection
    {
        return $this->evaluations;
    }

    public function addEvaluation(Evaluation $evaluation): self
    {
        if (!$this->evaluations->contains($evaluation)) {
            $this->evaluations[] = $evaluation;
            $evaluation->setSession($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->contains($evaluation)) {
            $this->evaluations->removeElement($evaluation);
            // set the owning side to null (unless already changed)
            if ($evaluation->getSession() === $this) {
                $evaluation->setSession(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection|Historiquesession[]
     */
    public function getHistoriquesessions(): Collection
    {
        return $this->historiquesessions;
    }

    public function addHistoriquesession(Historiquesession $historiquesession): self
    {
        if (!$this->historiquesessions->contains($historiquesession)) {
            $this->historiquesessions[] = $historiquesession;
            $historiquesession->setSession($this);
        }

        return $this;
    }

    public function removeHistoriquesession(Historiquesession $historiquesession): self
    {
        if ($this->historiquesessions->contains($historiquesession)) {
            $this->historiquesessions->removeElement($historiquesession);
            // set the owning side to null (unless already changed)
            if ($historiquesession->getSession() === $this) {
                $historiquesession->setSession(null);
            }
        }

        return $this;
    }

    public function getAnnee(): ?string
    {
        return $this->annee;
    }

    public function setAnnee(string $annee): self
    {
        $this->annee = $annee;

        return $this;
    }

    public function getMois(): ?string
    {
        return $this->mois;
    }

    public function setMois(string $mois): self
    {
        $this->mois = $mois;

        return $this;
    }
}
