<?php

namespace App\Entity;

use App\Repository\GenreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: GenreRepository::class)]
class Genre
{
  #[ORM\Id]
  #[ORM\GeneratedValue]
  #[ORM\Column]
  #[Groups(['movie_browse', 'genre_browse', 'genre_read', 'movie_read'])]
  private ?int $id = null;

  #[ORM\Column(length: 255)]
  #[Assert\NotBlank]
  #[Groups(['movie_browse', 'genre_browse', 'genre_read', 'movie_read'])]
  private ?string $name = null;

  #[ORM\ManyToMany(targetEntity: Movie::class, mappedBy: 'genres')]
  #[Groups(['genre_read'])]
  private Collection $movies;

  public function __construct()
  {
    $this->movies = new ArrayCollection();
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function getName(): ?string
  {
    return $this->name;
  }

  public function setName(string $name): self
  {
    $this->name = $name;

    return $this;
  }

  /**
   * @return Collection<int, Movie>
   */
  public function getMovies(): Collection
  {
    return $this->movies;
  }

  public function addMovie(Movie $movie): self
  {
    if (!$this->movies->contains($movie)) {
      $this->movies->add($movie);
      $movie->addGenre($this);
    }

    return $this;
  }

  public function removeMovie(Movie $movie): self
  {
    if ($this->movies->removeElement($movie)) {
      $movie->removeGenre($this);
    }

    return $this;
  }
}
