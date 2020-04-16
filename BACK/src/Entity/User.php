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
     * @Groups({"grow"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"grow"})
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
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow", "externe"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $statut;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"grow", "externe"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Groups({"grow", "externe"})
     */
    private $poste;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\UserTeamPromo", mappedBy="user")
     */
    private $userTeamPromos;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow", "externe"})
     */
    private $image;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Structure", inversedBy="users")
     */
    private $structure;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $nomtuteur;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephonetuteur;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="users")
     */
    private $mentor;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="mentor")
     */
    private $users;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Evaluation", mappedBy="evaluer")
     */
    private $evaluations;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Historiquesession", mappedBy="user")
     */
    private $historiquesessions;

    public function __construct()
    {
        $this->userTeamPromos = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->evaluations = new ArrayCollection();
        $this->historiquesessions = new ArrayCollection();
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
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): self
    {
        $this->statut = $statut;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

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

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

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

    public function getNomtuteur(): ?string
    {
        return $this->nomtuteur;
    }

    public function setNomtuteur(?string $nomtuteur): self
    {
        $this->nomtuteur = $nomtuteur;

        return $this;
    }

    public function getTelephonetuteur(): ?string
    {
        return $this->telephonetuteur;
    }

    public function setTelephonetuteur(?string $telephonetuteur): self
    {
        $this->telephonetuteur = $telephonetuteur;

        return $this;
    }

    public function getMentor(): ?self
    {
        return $this->mentor;
    }

    public function setMentor(?self $mentor): self
    {
        $this->mentor = $mentor;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(self $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setMentor($this);
        }

        return $this;
    }

    public function removeUser(self $user): self
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getMentor() === $this) {
                $user->setMentor(null);
            }
        }

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
            $evaluation->setEvaluer($this);
        }

        return $this;
    }

    public function removeEvaluation(Evaluation $evaluation): self
    {
        if ($this->evaluations->contains($evaluation)) {
            $this->evaluations->removeElement($evaluation);
            // set the owning side to null (unless already changed)
            if ($evaluation->getEvaluer() === $this) {
                $evaluation->setEvaluer(null);
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
            $historiquesession->setUser($this);
        }

        return $this;
    }

    public function removeHistoriquesession(Historiquesession $historiquesession): self
    {
        if ($this->historiquesessions->contains($historiquesession)) {
            $this->historiquesessions->removeElement($historiquesession);
            // set the owning side to null (unless already changed)
            if ($historiquesession->getUser() === $this) {
                $historiquesession->setUser(null);
            }
        }

        return $this;
    }

}
