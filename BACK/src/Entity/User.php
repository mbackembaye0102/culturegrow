<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User implements UserInterface
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     * @Groups({"grow", "externe"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"grow", "externe"})
     */
    private $username;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow", "externe"})
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow", "externe"})
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $Statut;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow", "externe"})
     */
    private $Telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow", "externe"})
     */
    private $poste;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserTeamPromo", mappedBy="user")
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->username;
    }

    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
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

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getStatut(): ?string
    {
        return $this->Statut;
    }

    public function setStatut(string $Statut): self
    {
        $this->Statut = $Statut;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->Telephone;
    }

    public function setTelephone(string $Telephone): self
    {
        $this->Telephone = $Telephone;

        return $this;
    }

    public function getPoste(): ?string
    {
        return $this->poste;
    }

    public function setPoste(string $poste): self
    {
        $this->poste = $poste;

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
            $userTeamPromo->setUser($this);
        }

        return $this;
    }

    public function removeUserTeamPromo(UserTeamPromo $userTeamPromo): self
    {
        if ($this->userTeamPromos->contains($userTeamPromo)) {
            $this->userTeamPromos->removeElement($userTeamPromo);
            // set the owning side to null (unless already changed)
            if ($userTeamPromo->getUser() === $this) {
                $userTeamPromo->setUser(null);
            }
        }

        return $this;
    }

}
