<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
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
     * @Groups({"grow", "externe"})                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\TeamPromo", inversedBy="userTeamPromos")
     * @Groups({"infos"}) 
     */
    private $TeamPromo;

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
        return $this->TeamPromo;
    }

    public function setTeamPromo(?TeamPromo $TeamPromo): self
    {
        $this->TeamPromo = $TeamPromo;

        return $this;
    }
}
