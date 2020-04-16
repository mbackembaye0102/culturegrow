<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\HistoriquesessionRepository")
 */
class Historiquesession
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="historiquesessions")
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TeamPromo", inversedBy="historiquesessions")
     */
    private $team;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Allsession", inversedBy="historiquesessions")
     */
    private $session;


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

    public function getTeam(): ?TeamPromo
    {
        return $this->team;
    }

    public function setTeam(?TeamPromo $team): self
    {
        $this->team = $team;

        return $this;
    }

    public function getSession(): ?Allsession
    {
        return $this->session;
    }

    public function setSession(?Allsession $session): self
    {
        $this->session = $session;

        return $this;
    }
}
