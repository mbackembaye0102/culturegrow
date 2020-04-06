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

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Allsession", mappedBy="structure")
     */
    private $allsessions;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="structure")
     */
    private $users;

    public function __construct()
    {
        $this->teamPromos = new ArrayCollection();
        $this->allsessions = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection|Allsession[]
     */
    public function getAllsessions(): Collection
    {
        return $this->allsessions;
    }

    public function addAllsession(Allsession $allsession): self
    {
        if (!$this->allsessions->contains($allsession)) {
            $this->allsessions[] = $allsession;
            $allsession->setStructure($this);
        }

        return $this;
    }

    public function removeAllsession(Allsession $allsession): self
    {
        if ($this->allsessions->contains($allsession)) {
            $this->allsessions->removeElement($allsession);
            // set the owning side to null (unless already changed)
            if ($allsession->getStructure() === $this) {
                $allsession->setStructure(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setStructure($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getStructure() === $this) {
                $user->setStructure(null);
            }
        }

        return $this;
    }
}
