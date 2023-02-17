<?php

namespace App\DataFixtures;

use App\DataFixtures\Providers\GenreProvider;
use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Season;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager c'est le parent de EntityManager, c'est lui qui demande le persist() et le flush()
     */
    public function load(ObjectManager $manager): void
    {
        
        // use the factory to create a Faker\Generator instance
        // use Faker\Factory;
        //? https://fakerphp.github.io/locales/fr_FR/
        $faker = Factory::create('fr_FR');

        //on ajoute notre provider, on va pouvoir l'utiliser comme si c'était inclus dans le faker
        $faker->addProvider(new GenreProvider($faker));

        // Pour avoir toujours les mêmes données (le même hasard)
        //? https://fakerphp.github.io/#seeding-the-generator
        $faker->seed(2022);

        // TODO : person
        $allPerson = [];
        // 300 films, 3 acteurs/film ~ 900
        for ($i=0; $i < 900; $i++) { 
            $person = new Person();
            $person->setFirstname($faker->firstName());
            $person->setLastname($faker->lastName());

            $manager->persist($person);

            $allPerson[] = $person;
        }


        // TODO : les genres
        $allGenre = [];
        // utilisation de notre provider
        foreach ($faker->getGenres() as $genreName) {
            $genre = new Genre();
            $genre->setName($genreName);

            $manager->persist($genre);
            $allGenre[] = $genre;
        }

        // 2. associer 1 ou plusieurs genres à tout nos films/serie

        // 2bis : comment retrouver mes films ?
        // solution 1 : faire des requetes dans la BDD: defaut il faut faire le flush avant, et c'est pas optimisé
        // solution 2 : conserver la liste des genres dans un tableau
        //? $allGenre contient tout les genres
        // et pendant la génération de Movie, faire une association aléatoirement

        
        // TODO : je veux des saisons, uniquement pour les movie qui sont de type serie

        // 1. faire un random sur film OU serie

        // 2. si le film est de type 'serie', alors générer X season

        // TODO : je veux des films, PLEIN de films !!!!!!!!
        $allMovies = [];
        for ($i=0; $i < 300; $i++) { 
            // les rushs :D
            $movie = new Movie();
            $movie->setTitle("Film #" . $i);

            // TODO : random film/serie
            //? https://fakerphp.github.io/formatters/numbers-and-strings/#randomelement
            $movie->setType($faker->randomElement(['film', 'serie']));

            // * gestion des seasons
            if ($movie->getType() === 'serie'){
                $nbSeason = rand(1,10);
                //! attention à ne pas utiliser le même index dans des boucles imbriquées
                //? mini = 1, et maxi = nbSeason
                for ($j=1; $j <= $nbSeason; $j++) { 
                    $season = new Season();
                    // de 1 à nbSeason
                    $season->setNumber($j);
                    // TODO faire mieux, plus random :D
                    $season->setNbEpisode(24);

                    $manager->persist($season);
                    // ne pas oublier de l'ajouter à la série
                    $movie->addSeason($season);
                }
            }

            // * gestion des genres
            // TODO : associer plusieurs genre, entre 1 et 4 genres : rand(1,4)
            // boucler de 1 à X, suivant le nombre de genre sélectionner au préalable
            // récupère la liste des genres existant dans Movie
            $genresAlreadyAdded[] = $movie->getGenres();
            // entre 1 et 4 genre
            for ($k=0; $k<rand(1, 5); $k++) {
                // sélection aléatoire
                $genreToAdd = $allGenre[rand(0, count($allGenre)-1)];
                
                // check que le genre aléatoire n'est pas dans la liste déjà existante
                if (!in_array($genreToAdd, $genresAlreadyAdded)) {
                    // si il n'est pas dans la liste on l'ajoute
                    $movie->addGenre($genreToAdd);
                    // je l'ajoute à la liste des présents
                    $genresAlreadyAdded[] = $genre;
                }
            }
            /*
            * version avec un seul genre
            $randIndexGenre = rand(0, count($allGenre) - 1);
            // on va chercher un genre aléatoire
            $randomGenre = $allGenre[$randIndexGenre];
            $movie->addGenre($randomGenre);
            */
            //? https://fakerphp.github.io/formatters/date-and-time/#datetimebetween
            $movie->setReleaseDate($faker->dateTimeBetween('-100 years'));
            //? https://fakerphp.github.io/formatters/numbers-and-strings/#numberbetween
            $movie->setDuration($faker->numberBetween(30, 263));
            //? https://fakerphp.github.io/formatters/numbers-and-strings/#randomfloat
            $movie->setRating($faker->randomFloat(1, 1, 5));

            $movie->setPoster("https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg");

            // un sentence va contenir 6 mots, ce qui ressemble à un résumé
            $movie->setSummary($faker->sentence());
            // on génère un paragraphe            
            $movie->setSynopsis($faker->paragraph());

            $movie->setCountry("FR");

            //! on oublie pas le persist
            $manager->persist($movie);

            $allMovies[] = $movie;
        }


        // TODO : casting
        // il me faut 1 movie ($allMovies) et 1 person ($allPerson)
        // donner un nom de role
        // donner un crédit order
        // on ne peut pas faire une boucle de créatin de casting
        // ca on veut que TOUT les films ai au moins un casting associé
        // ? on va plutot partie de liste des movies
        foreach ($allMovies as $movie) {
            // TODO : plusieur casting, donc une boucle de création de casting, donc un nombre aléatoire
            $nbCasting = rand(1,4);
            // TODO : dans la boucle de casting, de la même façon que Genre on va devoir check si l'acteur est déjà présent OU pas
            for ($i=1; $i <= $nbCasting; $i++) {
                // TODO : utiliser le faker pour toutes les chaines de caractères.
                $casting = new Casting();
                // j'associe le film
                $casting->setMovie($movie);
                // la personne aléatoire
                $randIndexPerson = rand(0, count($allPerson) -1);
                $randPerson = $allPerson[$randIndexPerson];
                $casting->setPerson($randPerson);
                // donner un nom de role
                //? https://fakerphp.github.io/#create-fake-data
                $casting->setRole($faker->name());
                // donner un crédit order
                //! on met 1 car on a qu'un casting
                // TODO : faire le creditOrder correctement
                $casting->setCreditOrder($i);
            }

            $manager->persist($casting);
        }

        $manager->flush();
    }
}

