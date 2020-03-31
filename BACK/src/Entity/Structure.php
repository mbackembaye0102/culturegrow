<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\StructureRepository")
 */
class Structure
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grow", "externe"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow", "externe"})
     */
    private $nom;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\TeamPromo", mappedBy="structure")
     */
    private $teamPromos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"grow", "externe"})
     */
    private $image;

    public function __construct()
    {
        $this->teamPromos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * @return Collection|TeamPromo[]
     */
    public function getTeamPromos(): Collection
    {
        return $this->teamPromos;
    }

    public function addTeamPromo(TeamPromo $teamPromo): self
    {
        if (!$this->teamPromos->contains($teamPromo)) {
            $this->teamPromos[] = $teamPromo;
            $teamPromo->setStructure($this);
        }

        return $this;
    }

    public function removeTeamPromo(TeamPromo $teamPromo): self
    {
        if ($this->teamPromos->contains($teamPromo)) {
            $this->teamPromos->removeElement($teamPromo);
            // set the owning side to null (unless already changed)
            if ($teamPromo->getStructure() === $this) {
                $teamPromo->setStructure(null);
            }
        }

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }
}
