<?php

namespace App\Command;

use App\Repository\MovieRepository;
use App\Services\MySlugger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
  name: 'app:services:sluggify',
  description: 'Update in DB all slugs with lower case configuration in folder .env or with command option',
)]
class SluggifyCommand extends Command
{
  private $movieRepository;
  private $mySlugger;
  private $entityManager;

  public function __construct(MovieRepository $movieRepository, MySlugger $mySlugger, EntityManagerInterface $entityManager)
  {
    parent::__construct();

    $this->movieRepository = $movieRepository;
    $this->mySlugger = $mySlugger;
    $this->entityManager = $entityManager;
  }

  protected function configure(): void
  {
    $this
      ->addArgument('idMovie', InputArgument::OPTIONAL, 'Movie ID for update Slug.')
      ->addOption('lower', null, InputOption::VALUE_NONE, 'force update slug in lower case');
  }

  protected function execute(InputInterface $input, OutputInterface $output): int
  {
    $io = new SymfonyStyle($input, $output);

    $idMovie = $input->getArgument('idMovie');

    if ($idMovie) {
      $io->note(sprintf('You passed an argument: %s', $idMovie));
      $movie = $this->movieRepository->find($idMovie);
      if ($movie) {
        $slug = $this->mySlugger->slugify($movie->getTitle());
        if ($input->getOption('lower')) {
          $slug = strtolower($slug);
        }
        $movie->setSlug($slug);
        $this->entityManager->flush();
        $io->success('Le film ' . $movie->getTitle() . 'a bien été modifié');
        return Command::SUCCESS;
      } else {
        $io->error('Le film ' . $idMovie . ' n\'existe pas en BDD');
        return Command::FAILURE;
      }
    }

    $allMovies = $this->movieRepository->findAll();

    $io->note('il y a ' . count($allMovies) . ' films à mettre à jour');

    foreach ($allMovies as $movie) {
      $title = $movie->getTitle();
      $slug = $this->mySlugger->slugify($title);
      if ($input->getOption('lower')) {
        $slug = strtolower($slug);
      }
      $movie->setSlug($slug);
      $io->text('Slug generation for movie : ' . $movie->getTitle() . ' Slug : ' . $slug);
    }

    $this->entityManager->flush();

    $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

    return Command::SUCCESS;
  }
}
