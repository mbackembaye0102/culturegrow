<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\UserTeamPromoRepository")
 */
class UserTeamPromo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="userTeamPromos")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TeamPromo", inversedBy="userTeamPromos")
     */
    private $teamPromo;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getTeamPromo(): ?TeamPromo
    {
        return $this->teamPromo;
    }

    public function setTeamPromo(?TeamPromo $teamPromo): self
    {
        $this->teamPromo = $teamPromo;

        return $this;
    }
}
