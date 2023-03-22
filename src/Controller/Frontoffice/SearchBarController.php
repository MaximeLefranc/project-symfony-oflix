<?php

namespace App\Controller\Frontoffice;

use App\Repository\GenreRepository;
use App\Repository\MovieRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchBarController extends AbstractController
{
    #[Route('/recherche', name: 'app_frontoffice_search_bar')]
    public function searchBar(Request $request, MovieRepository $movieRepository, GenreRepository $genreRepository, PaginatorInterface $paginatorInterface): Response
    {
        $inputUser = $request->get('search');
        $researchedMovies = $movieRepository->findMovieBySearchBar($inputUser);

        if (!$researchedMovies) {
            throw $this->createNotFoundException('Pas de films trouvés avec la recherche: ' . $inputUser);
        }

        $moviesToShow = $paginatorInterface->paginate(
            $researchedMovies, // Requête contenant les données à paginer 
            $request->query->getInt('page', 1), // Numéro de la page en cours, passé dans l'URL, 1 si aucune page
            6 // Nombre de résultats par page
        );

        return $this->render('frontoffice/search_bar/index.html.twig', [
            'allGenres' => $genreRepository->findAll(),
            'title' => 'Votre recherche: ' . $inputUser,
            'movieToShow' => $moviesToShow,
        ]);
    }
}
