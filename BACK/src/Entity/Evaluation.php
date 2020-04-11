<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource()
 * @ORM\Entity(repositoryClass="App\Repository\EvaluationRepository")
 */
class Evaluation
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="evaluations")
     * @Groups({"grow"})
     */
    private $evaluer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="evaluations")
     * @Groups({"grow"})
     */
    private $evaluateur;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $perseverance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $confiance;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $collaboration;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $autonomie;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $problemsolving;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $transmission;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"grow"})
     */
    private $performance;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Allsession", inversedBy="evaluations")
     */
    private $session;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $team;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvaluer(): ?User
    {
        return $this->evaluer;
    }

    public function setEvaluer(?User $evaluer): self
    {
        $this->evaluer = $evaluer;

        return $this;
    }

    public function getEvaluateur(): ?User
    {
        return $this->evaluateur;
    }

    public function setEvaluateur(?User $evaluateur): self
    {
        $this->evaluateur = $evaluateur;

        return $this;
    }

    public function getPerseverance(): ?string
    {
        return $this->perseverance;
    }

    public function setPerseverance(string $perseverance): self
    {
        $this->perseverance = $perseverance;

        return $this;
    }

    public function getConfiance(): ?string
    {
        return $this->confiance;
    }

    public function setConfiance(string $confiance): self
    {
        $this->confiance = $confiance;

        return $this;
    }

    public function getCollaboration(): ?string
    {
        return $this->collaboration;
    }

    public function setCollaboration(string $collaboration): self
    {
        $this->collaboration = $collaboration;

        return $this;
    }

    public function getAutonomie(): ?string
    {
        return $this->autonomie;
    }

    public function setAutonomie(string $autonomie): self
    {
        $this->autonomie = $autonomie;

        return $this;
    }

    public function getProblemsolving(): ?string
    {
        return $this->problemsolving;
    }

    public function setProblemsolving(string $problemsolving): self
    {
        $this->problemsolving = $problemsolving;

        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): self
    {
        $this->transmission = $transmission;

        return $this;
    }

    public function getPerformance(): ?string
    {
        return $this->performance;
    }

    public function setPerformance(string $performance): self
    {
        $this->performance = $performance;

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

    public function getTeam(): ?string
    {
        return $this->team;
    }

    public function setTeam(string $team): self
    {
        $this->team = $team;

        return $this;
    }

}
