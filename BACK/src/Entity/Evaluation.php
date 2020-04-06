<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

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
     */
    private $evaluer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="evaluations")
     */
    private $evaluateur;

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
}
