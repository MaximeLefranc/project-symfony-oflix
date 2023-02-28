<?php

namespace App\Services;

use Symfony\Component\String\Slugger\SluggerInterface;

class MySlugger
{
  private $slugger;
  private $isLowerCase;

  public function __construct(SluggerInterface $sluggerInterface, $lower)
  {
    $this->slugger = $sluggerInterface;
    $this->isLowerCase = $lower;
  }

  public function slugify($title)
  {
    $slug = $this->slugger->slug($title);
    if ($this->isLowerCase) {
      return $slug->lower();
    }
    return $slug;
  }
}
