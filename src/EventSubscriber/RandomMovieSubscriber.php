<?php

namespace App\EventSubscriber;

use App\Repository\MovieRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Twig\Environment;

class RandomMovieSubscriber implements EventSubscriberInterface
{
	private $movieRepository;
	private $twig;

	public function __construct(MovieRepository $movieRepository, Environment $twig)
	{
		$this->movieRepository = $movieRepository;
		$this->twig = $twig;
	}

	public function onKernelController(ControllerEvent $event): void
	{
		$pathinfo = $event->getRequest()->getPathInfo();
		if (preg_match('/^\/(api|backoffice)/', $pathinfo)) {
			return;
		}

		$movie = $this->movieRepository->findRandomMovie();
		$this->twig->addGlobal('randomMovie', $movie);
	}

	public static function getSubscribedEvents(): array
	{
		return [
			// Event name asociated with the name method
			KernelEvents::CONTROLLER => 'onKernelController',
		];
	}
}
