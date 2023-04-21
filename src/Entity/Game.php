<?php

namespace App\Entity;

use App\Repository\GameRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GameRepository::class)]
class Game
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $squence = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $proposal = null;

    #[ORM\ManyToOne(inversedBy: 'games')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSquence(): ?string
    {
        return $this->squence;
    }

    public function setSquence(string $squence): self
    {
        $this->squence = $squence;

        return $this;
    }

    public function getProposal(): ?string
    {
        return $this->proposal;
    }

    public function setProposal(?string $proposal): self
    {
        $this->proposal = $proposal;

        return $this;
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
}
