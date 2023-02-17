<?php

namespace App\DataFixtures;

use App\Entity\Casting;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Season;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{   
    private $firstname = [
        'Martine',
        'Eduardo',
        'Maxime',
        'Romain',
        'Sophie',
        'Pierre Yves',
        'Sofiane',
        'Patrick',
        'Antoine',
        'Blanca'
    ];

    private $lastname = [
        'Dupont',
        'Durant',
        'Lefranc',
        'Bailhé',
        'Echeverry',
        'Monto',
        'Lemont',
        'Cavalier',
        'Bourdon',
        'Gelis'
    ];

    private $role = [
        'Le bouffon',
        'La none',
        'La pute',
        'Bagherra',
        'Arsène',
        'Lupin',
        'Codeur',
        'Gentil',
        'Méchant',
    ];

    public function load(ObjectManager $manager): void
    {
        $genres = [];
        for ($i = 1; $i <= 50; $i++) { 
            $genre = new Genre();
            $genre->setName('Genre n°' . $i);
            $manager->persist($genre);
            $genres[] = $genre;
        };

        $persons = [];
        for ($i=0; $i < 20 ; $i++) { 
            $person = new Person();
            $person->setFirstname($this->firstname[rand(0, count($this->firstname) - 1)]);
            $person->setLastname($this->lastname[rand(0, count($this->lastname) - 1)]);
            $manager->persist($person);
            $persons[] = $person;
        }

        for ($i = 0; $i < 300; $i++) {

            $movie = new Movie();
            $movie->setTitle('Film #' . $i);

            $type = rand(1,2) === 1 ? 'film' : 'serie';
            $movie->setType($type);

            if ($type === 'serie') {
                $nbSeason = rand(1,10);
                for ($j = 1; $j <= $nbSeason; $j++) {
                    $season = new Season();
                    $season->setNumber($j);
                    $season->setNbEpisode(rand(5, 24));
                    $manager->persist($season);
                    $movie->addSeason($season);
                }
            }

            $genreAllreadyAdded[] = $movie->getGenres();
            for ($k=0; $k <= rand(1, 4); $k++) { 
                $genreToAdd = $genres[rand(0, count($genres) - 1)];

                if (!in_array($genreToAdd, $genreAllreadyAdded)) {
                    $movie->addGenre($genreToAdd);
                    $genreAllreadyAdded[] = $genreToAdd;
                } else {
                    $k--;
                }
            }

            for ($l=1; $l < rand(2, 4) ; $l++) { 
                $casting = new Casting();
                $casting->setRole($this->role[rand(0, count($this->role) - 1)]);
                $casting->setCreditOrder($l);
                $casting->setPerson($persons[rand(0, count($persons) - 1)]);
                $casting->setMovie($movie);
                $manager->persist($casting);
            }

            // $movie->setPoster('https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg');
            $movie->setDuration(rand(10,300));
            $movie->setReleaseDate(new DateTime());
            $manager->persist($movie);
        }

        $manager->flush();
    }
}
