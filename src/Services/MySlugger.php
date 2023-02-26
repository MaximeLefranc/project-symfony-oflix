<?php

namespace App\Services;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class MySlugger
{
  private $slugger;
  private $isLowerCase;

  public function __construct(SluggerInterface $sluggerInterface, ParameterBagInterface $params)
  {
    $this->slugger = $sluggerInterface;
    $this->isLowerCase = $params->get('app.myslugger.islower');
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
