# Recap API

## controller et pas de template

Quand on est en mode "API", on ne veut plus utiliser les templates de twig
Il existe une option de commande pour ne pas générer de template

```bash
bin/console make:controller --no-template
```

Le maker est sympa, il comprends que l'on va faire de l'API, il remplace le `$this->render()` par un `$this->json()`

## JSON et les Groups

Quand on est en mode "API", notre objectif est de renvoyer du JSON.

Simple en PHP, on `serialize` nos objets, mais avec doctrine et les relations entre nos objets, ben c'est pas la même.

Pourquoi ?

Parce que Doctrine est trop sympa, il nous ramène tout ce qu'on lui demande dès qu'on lui demande.
Donc quand on transforme un objet en JSON, on parcours toutes ses propriétés, et Doctrine fait son taf 💥

Pour cela Symfony nous propose de faire des annotations `@Groups` sur chaque propriété pour pouvoir bien spécifier ce que l'on veux renvoyer comme données.

[doc](https://symfony.com/doc/5.4/components/serializer.html)

```bash
composer require symfony/serializer
```

```php
use Symfony\Component\Serializer\Annotation\Groups;

/*
* @Groups({"get_movies"})
*/
```

On peut mettre plusieur nom de groupe sur une propriété

```php
/*
* @Groups({"get_movies", "get_movies_collection"})
*/
```

Il ne nous reste plus qu'a dire à Symfony quel groupe utiliser pour serializer notre json

```php
return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $moviesList,
            // Le status code
            Reponse::HTTP_OK,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => ['get_movies_collection']]
        );
```

Super tout ça, mais ça va devenir rapidement compliqué si on a une API bien fournie.

Une idée de bonne pratique est d'utiliser des noms de groupe par entité :

* Movies : get_movies, get_movies_collection
* Genres : get_genres, get_genres_collection

Donc si je veux renvoyer un `movie` avec ses `genre`, on va pouvoir préciser tout les groupes à utiliser.

```php
return $this->json(
            // Les données à sérialiser (à convertir en JSON)
            $moviesListWithGenre,
            // Le status code
            200,
            // Les en-têtes de réponse à ajouter (aucune)
            [],
            // Les groupes à utiliser par le Serializer
            ['groups' => [
                'get_movies_collection',
                'get_genres_collection'
                ]
            ]
        );
```
