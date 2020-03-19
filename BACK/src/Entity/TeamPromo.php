<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\TeamPromoRepository")
 */
class TeamPromo
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Structure", inversedBy="teamPromos")
     */
    private $structure;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserTeamPromo", mappedBy="TeamPromo")
     */
    private $userTeamPromos;


    public function __construct()
    {
        $this->userTeamPromos = new ArrayCollection();
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

    public function getStructure(): ?Structure
    {
        return $this->structure;
    }

    public function setStructure(?Structure $structure): self
    {
        $this->structure = $structure;

        return $this;
    }

    /**
     * @return Collection|UserTeamPromo[]
     */
    public function getUserTeamPromos(): Collection
    {
        return $this->userTeamPromos;
    }

    public function addUserTeamPromo(UserTeamPromo $userTeamPromo): self
    {
        if (!$this->userTeamPromos->contains($userTeamPromo)) {
            $this->userTeamPromos[] = $userTeamPromo;
            $userTeamPromo->setTeamPromo($this);
        }

        return $this;
    }

    public function removeUserTeamPromo(UserTeamPromo $userTeamPromo): self
    {
        if ($this->userTeamPromos->contains($userTeamPromo)) {
            $this->userTeamPromos->removeElement($userTeamPromo);
            // set the owning side to null (unless already changed)
            if ($userTeamPromo->getTeamPromo() === $this) {
                $userTeamPromo->setTeamPromo(null);
            }
        }

        return $this;
    }

}
