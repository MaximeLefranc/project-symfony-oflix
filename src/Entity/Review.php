<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
class Review
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['movie_read'])]
  private ?int $id = null;

  #[ORM\Column(length: 50)]
  #[Assert\NotBlank(message: 'Veuillez choisir un pseudo.')]
  #[Assert\Length(
    min: 2,
    max: 50,
    minMessage: 'Le pesudo doit contenir au minimum {{ limit }} caratères',
    maxMessage: 'Le psueod doit contenir au maximum {{ limit }} caractères',
  )]
  #[Groups(['movie_read'])]
  private ?string $username = null;

  #[ORM\Column(length: 255)]
  #[Assert\NotBlank(message: 'Veuillez entrer une adresse mail valide')]
  #[Groups(['movie_read'])]
  private ?string $email = null;

  #[ORM\Column(type: Types::TEXT)]
  #[Assert\NotBlank(message: 'Quelques mots sur vos impressions !')]
  #[Groups(['movie_read'])]
  private ?string $content = null;

  #[ORM\Column]
  #[Assert\NotBlank(message: 'Mettez au moins une étoile.')]
  #[Groups(['movie_read'])]
  private ?float $rating = null;

  #[ORM\Column]
  #[Assert\NotBlank(message: 'Veuillez sélectionner au moins uen réaction.')]
  #[Groups(['movie_read'])]
  private array $reactions = [];

  #[ORM\Column(type: Types::DATETIME_MUTABLE)]
  #[Assert\NotNull(message: 'Quand avez vous regardé ce film ?')]
  #[Groups(['movie_read'])]
  private ?\DateTimeInterface $watchedAt = null;

  #[ORM\ManyToOne(inversedBy: 'reviews')]
  private ?Movie $movie = null;

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getUsername(): ?string
  {
    return $this->username;
  }

  public function setUsername(string $username): self
  {
    $this->username = $username;

    return $this;
  }

  public function getEmail(): ?string
  {
    return $this->email;
  }

  public function setEmail(string $email): self
  {
    $this->email = $email;

    return $this;
  }

  public function getContent(): ?string
  {
    return $this->content;
  }

  public function setContent(string $content): self
  {
    $this->content = $content;

    return $this;
  }

  public function getRating(): ?float
  {
    return $this->rating;
  }

  public function setRating(float $rating): self
  {
    $this->rating = $rating;

    return $this;
  }

  public function getReactions(): array
  {
    return $this->reactions;
  }

  public function setReactions(array $reactions): self
  {
    $this->reactions = $reactions;

    return $this;
  }

  public function getWatchedAt(): ?\DateTimeInterface
  {
    return $this->watchedAt;
  }

  public function setWatchedAt(\DateTimeInterface $watchedAt): self
  {
    $this->watchedAt = $watchedAt;

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
