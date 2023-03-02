<?php

namespace App\Entity;

use App\Repository\CastingRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: CastingRepository::class)]
class Casting
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['movie_read'])]
  private ?int $id = null;

  #[ORM\Column(length: 128)]
  #[Groups(['movie_read'])]
  private ?string $role = null;

  #[ORM\Column]
  #[Groups(['movie_read'])]
  private ?int $creditOrder = null;

  #[ORM\ManyToOne(inversedBy: 'castings')]
  #[ORM\JoinColumn(nullable: false)]
  #[Groups(['movie_read'])]
  private ?Person $person = null;

  #[ORM\ManyToOne(inversedBy: 'castings')]
  #[ORM\JoinColumn(nullable: false)]
  private ?Movie $movie = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getRole(): ?string
  {
    return $this->role;
  }

  public function setRole(string $role): self
  {
    $this->role = $role;

    return $this;
  }

  public function getCreditOrder(): ?int
  {
    return $this->creditOrder;
  }

  public function setCreditOrder(int $creditOrder): self
  {
    $this->creditOrder = $creditOrder;

    return $this;
  }

  public function getPerson(): ?Person
  {
    return $this->person;
  }

  public function setPerson(?Person $person): self
  {
    $this->person = $person;

    return $this;
  }

  public function getMovie(): ?Movie
  {
    return $this->movie;
  }

  public function setMovie(?Movie $movie): self
  {
    $this->movie = $movie;

    return $this;
  }
}
