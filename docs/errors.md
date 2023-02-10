# Errors

## [InvalidArgumentException] Could not find package annotations

arrive quand j'essaye d'installer `composer require annotations`

Pourquoi : je n'ai pas la bonne version de composer, ou composer n'est pas à jour.

Solution : taper la commande : `sudo composer self-update`

le 24/10/22 on a la version 2.4.3

## [Semantical Error] The annotation "@Route" in method xxxx was never imported. Did you maybe forget to add a "use" statement for this annotation?

j'ai oublié d'ajouter le use pour utiliser les annotations dans symfony : `use Symfony\Component\Routing\Annotation\Route;`

## [Syntax Error] Expected Doctrine\Common\Annotations\DocLexer::T_CLOSE_PARENTHESIS

```text
[Syntax Error] Expected Doctrine\Common\Annotations\DocLexer::T_CLOSE_PARENTHESIS, got '/' at position 8 in method App\Controller\MainController::home()
```

```php
/*
* @Route("/) --> erreur
* @Route("/") --> OK
*/
```

## [Syntax Error] Expected PlainValue, got ''' at position 7 in method App\Controller\ModelController::show()

```php
/*
* @Route('/') --> erreur
* @Route("/") --> OK
*/
```

## Attempted to call an undefined method named "render" of class "App\Controller\DemoController"

On a oublié de faire un `extends AbstractController` sur notre controller

## No route found for "GET xxxxx"

utiliser le `bin/console debug:router` pour voir si la route existe

OU utiliser `bin/console router:match /bonjour` pour valider la route

```bash

 [ERROR] None of the routes match the path "/bonjour"                                                                                                                                                    
```

```bash
bin/console router:match /demo/bonjour

 [OK] Route "MaZolieRoute" matches
```

l'url de la route est sensible à la casse : les majuscules / minuscules vous jouent des tours.

## Twig : extends block (E02)

Si vous avez une erreur : `A template that extends another one cannot include content outside Twig blocks. Did you forget to put the content inside a {% block %} tag? c'est que vous avez mis du HTML en dehors de balises twig {% block %}`

ce problème n'apparait que si votre template commence par un `{% extends %}`

vous avez mis du code HTML en dehors de `{% block %}`
il faut alors mettre votre code/html dans un block, pour qu'il puisse être utilisé par le template parent

## SQLSTATE[xxxx] (E04+)

Si ma chaine de connexion n'est pas bonne, ou que l'utilisateur n'a pas les droits
j'ai cette erreur

```text
  SQLSTATE[HY000] [1045] Access denied for user 'app'@'localhost' (using password: YES)                                                                            
```

Solution : vérifer le fichier `.env.local` avec les chaines de connexion
Puis aller dans Adminer pour vérifier que mon utilisateur a bien les droits sur la BDD

Si le fichier `.env.local` n'est pas bien nommé, on aura cette erreur

### Erreur metadata

```text
The metadata storage is not up to date, please run the sync-metadata-storage command to fix this issue.
```

Je n'ai pas changé la chaine de connexion, je dois préciser mariadb

```text
?serverVersion=mariadb-10.3.25
```

## Unable to generate a URL for the named route "app_main" as such route does not exist

J'ai renommé le nom d'une route après avoir coder une redirection.

Il est dangeureux de renommer des routes en plein milieu du projet.

Solution mettre le bon nom de route dans la redirection / path()

## Object of class xxxxxxx could not be converted to string

Cette erreur arrive quand j'essai de faire un echo dans twig d'un objet

Il n'est pas possible d'écrire (echo ou {{}}) un objet, c'est une structure.

Solution : il faut utiliser une propriété ou utiliser un formatage

eg : pour les datetime, twig propose des filtres `|date('d/m/Y h:i:s')`

## A new entity was found through the relationship 'App\Entity\Post#author' that was not configured to cascade persist operations for entity: App\Entity\Author@593. To solve this issue: Either explicitly call EntityManager#persist() on this unknown entity

On a essayer d'ajouter une entité `Post` avec une entité `Author` via une relation.

On fait un persist sur l'entité `Post` mais pas sur l'entité `Author`

On doit faire un persist sur `Author` avant de faire le `flush`

## Return value of App\Repository\MovieRepository::findDQL() must be an instance of App\Entity\Movie or null, array returned

avec le `: ?Movie` j'oblige la function a renvoyer soit un objet Movie, soit NULL

```php
public function findDQL(int $id): ?Movie
{}
```

Apparement je renvoit un `array`, du coup il crie fort.

Solution aller voir pourquoi je renvoit un `array` au lieu d'un objet

## twig : Impossible to access an attribute ("title") on a null variable

```twig
{% block title %}    {{ movie.title }} - O'Flix{% endblock %}
```

on comprend que la variable `movie` est vide, donc on a pas de propriété associé.

On a donc pas récupéré de film.

## Could not load type "App\Controller\SubmitType": class does not exist

je n'arrive pas à afficher mon formulaire

je lit le message d'erreur .... le FQCN est ... pas le bon

solution : il manque un use

## Could not load type "Doctrine\DBAL\Types\TextType": class does not implement "Symfony\Component\Form\FormTypeInterface"

je n'arrive pas à afficher mon formulaire
je lit le message d'erreur, pourquoi il parle de Dotrine\DBAL ???

Solution : mon use n'est pas le bon
il faut utiliser `use Symfony\Component\Form\Extension\Core\Type\xxxxxxType`

## Expected argument of type "array", "null" given at property path "reactions"

Je suis en train de faire mon formulaire: WIP
Malheureusement le formulaire de make:form est 'incomplet', pas assez précis.

```php
// ReviewType
$builder->add('reactions');
// Entity 
private $reactions = [];
```

Les types ne correspondent pas, il faut finir notre formulaire

## Notice: Array to string conversion

Je suis en train de faire mon formulaire: WIP
Le choiceType doit être précis sur les valeurs possibles : il faut ajouter l'option 'choices'

## [Semantical Error] Couldn't find constant min, property App\Entity\Review::$username

je suis en train de mettre des contraintes de validation

[doc v6.1](https://symfony.com/doc/current/reference/constraints/Length.html)

```php
/**
 * @ORM\Column(type="string", length=50)
 * @Assert\NotBlank(message="ça va là fais pas ton radin mets au moins une étoile")
 * @Assert\Length(
 *   min: 2,
 *   max: 50,
 *   minMessage: 'Your first name must be at least {{ limit }} characters long',
 *   maxMessage: 'Your first name cannot be longer than {{ limit }} characters',
 *   )
 */
private $username;
```

et avec cette version ça fonctionne ... cherchez l'erreur ( = pas :) ET ( " pas ' )

[doc v5.4](https://symfony.com/doc/5.4/reference/constraints/Length.html#basic-usage)

```php
/**
 * @ORM\Column(type="string", length=50)
 * @Assert\NotBlank(message="ça va là fais pas ton radin mets au moins une étoile")
 * @Assert\Length(
 *      min = 2,
 *      max = 50,
 *      minMessage = "Your first name must be at least {{ limit }} characters long",
 *      maxMessage = "Your first name cannot be longer than {{ limit }} characters"
 * )
 */
private $username;
```

## Error form : This value should be of type string

j'ai ajouté une contrainte sur un champs `datetime_immutable`

```php
/**
 * @ORM\Column(type="datetime_immutable")
 * @Assert\NotBlank
 * @Assert\Date
 */
private $watchedAt;
```

dans mon formulaire j'ai précisé que ce champs doit être récupérer en `datetime_immutable`

```php
->add('watchedAt', DateType::class, ['input' => 'datetime_immutable']);
```

quand le formulaire fait le handleRequest() il transforme les valeurs en `datetime_immutable`
Notre assert attend une chaine de caractères.

Donc type incomptatible.

Si j'enlève le `datetime_immutable` du formulaire, la validaiton passe sans problème.
MAIS lorsque le formulaire tente de faire un setWatchedAt() il fait une erreur car il attend un `datetime_immutable`

```php
Expected argument of type "DateTimeImmutable", "instance of DateTime" given at property path "watchedAt".
```

On laisse donc le formulaire gérer le `datetime_immutable`, et on enlève l'assertion dans notre entité

## App\Entity\Movie object not found by the @ParamConverter annotation

J'utilise le paramConverter dans une méthode

```php
/**
 * @Route("/{id}", name="app_movie_show", methods={"GET"})
 */
public function show(Movie $movie): Response
{
  dd($movie);
}
```

Je lui fournit un ID qui n'existe pas, et même avec un `dd()` sur la première ligne, je n'y passe pas.

On arrive jamais dans notre méthode, l'erreur arrive avant.

Solution :

d'habitude on teste la nullité

```php
// gestion d'erreur
if (!$movie) {
    throw $this->createNotFoundException(
        'No movie found for id ' . $id
    );
}
```

On va donc autorisé le `null` en paramètre

```php
/**
 * @Route("/{id}", name="app_movie_show", methods={"GET"})
 */
public function show(Movie $movie = null): Response
{
  dd($movie);
}
```

## An error has occurred resolving the options of the form "Symfony\Bridge\Doctrine\Form\Type\EntityType": The required option "class" is missing

il faut préciser la classe liée

```php
->add('genres', EntityType::class, 
    [
        'class' => Genre::class
    ])
```

## Object of class App\Entity\Genre could not be converted to string

il faut préciser la propriété pour l'affichage

```php
->add('genres', EntityType::class, 
    [
        'class' => Genre::class,
        'choice_label' => 'name'
    ])
```

## Entity of type "Doctrine\ORM\PersistentCollection" passed to the choice field must be managed. Maybe you forget to persist it in the entity manager?

l'objectif est d'afficher une liste.
Liste <=> Collection

Comme on a précise le type de champs (EntityType) on doit tout lui dire, plus rien en auto.
Je veux une liste, je doit lui préciser l'option `multiple` / `expanded`
Ces deux options sont là pour changer l'aspect d'une liste/choix
[doc](https://symfony.com/doc/current/reference/forms/types/entity.html#multiple)

tableau de conversion HTML => ChoiceType

| Résultat HTML             | multiple | expended |
|:----------|:-------------:|:------:|
|checkbox         | true     | true     |
|radio button     |false     |true      |
|liste déroulante |false     |false     |
|liste déplié     |true      |false     |

## Unable to transform value for property path "movie": Expected a Doctrine\Common\Collections\Collection object

```php
->add('movie', EntityType::class,
        [
        'class' => Movie::class,
        'choice_label' => 'title',
        'multiple' => true,
        'expanded' => true
        ])
```

On dit à la génération de formulaire que l'on peut sélectionner plusieurs éléments : `'multiple' => true,`
multiple <=> Collection
Il ne peut y avoir qu'un seul `Movie` pour une `Season`, donc la génération de formulaire ne sais pas quoi faire.

## Expected to find class "App\DataFixtures\Providers\GenreProviders" in file "/var/www/html/inca/oflix/src/DataFixtures/Providers/GenreProviders.php"

Le nom du fichier PHP ne correspond pas au nom de la classe dans ce fichier
`GenreProviders`.php => class `Genreprovider`

## Expected argument of type "DateTimeInterface", "null" given at property path "releaseDate"

quand je soumet un formulaire avec une date NON REMPLIT j'ai cette erreur

cette erreur arrive au moment où on demande au formulaire de renseigner notre entité avec les valeurs fournies.

Problème : le setter de la propriété date n'accepte pas la valeur null

```php
public function setReleaseDate(\DateTimeInterface $releaseDate): self
{
    $this->releaseDate = $releaseDate;

    return $this;
}
```

Solution : autoriser la valeur `null` dans le setter : `?\DateTimeInterface`

```php
public function setReleaseDate(?\DateTimeInterface $releaseDate): self
{
    $this->releaseDate = $releaseDate;

    return $this;
}
```

## Neither the property "tagada" nor one of the methods "tagada()", "gettagada()"/"istagada()"/"hastagada()" or "__call()" exist and have public access in class "App\Entity\User"

je viens de mettre un affichage de propriété dans twig

La propriété ni aucune des méthodes décrites n'existent

Solution : vérifier la faute de frappe

## Cannot autowire argument $omdbApi of "App\Controller\MainController::browse()": it references class "App\Services\OmdbApi" but no such service exists

Je viens de créer un services, mais il ne se charge pas, peut être que composer n'arrive pas à le trouver.
Demandons lui de rafraichir sa liste de classes

```bash
composer dump-autoload
Generating optimized autoload files
Class App\Services\OmdbApi located in ./src/Services/OmbdApi.php does not comply with psr-4 autoloading standard. Skipping.
Generated optimized autoload files containing 4680 classes
```

le jeu des 7 erreurs (spoiler : y'en a qu'une)
`OmdbApi` != `OmbdApi`

reste plus qu'à refaire un dump-autoload

## An exception has been thrown during the rendering of a template ("Some mandatory parameters are missing ("slug") to generate a URL for route "movie_read".")

J'ai modifié les paramètre d'une route.

dans twig je n'ai pas mis à jour les paramètres de la route pour générer les liens

```php
/**
* @Route("/movie/{slug<[a-zA-Z0-9_-]+>}", name="movie_read")
*/
```

```twig
{# <a href="{{ path('movie_read', {id: movie.id }) }}" class="fs-1 mt-3 text-danger align-self-start"> #}
<a href="{{ path('movie_read', {slug: movie.slug }) }}" class="fs-1 mt-3 text-danger align-self-start">
```

## Argument 1 passed to Doctrine\ORM\Persisters\Entity\BasicEntityPersister::getSelectConditionStatementColumnSQL() must be of the type string, int given

le message d'erreur ne nius aide pas vraiment, mais on a la ligne où est l'erreur et ça ça aide 🎉

```php
$movie = $movieRepository->findOneBy([
            // critères de sélection
            [
                "slug" => $slug
            ]
        ]);
```

```php
$movie = $movieRepository->findOneBy(
            // critères de sélection
            [
                $slug
            ]
        );
```

premier cas : il y a des crochets en trop 🤦
deuxième cas : il y a des crochets en moins 🤦

```php
$movie = $movieRepository->findOneBy(
            // critères de sélection
            [
                "slug" => $slug
            ]
        );
```

## An exception has been thrown during the rendering of a template ("Some mandatory parameters are missing ("Slug") to generate a URL for route "movie_read".")

```php
/**
* @Route("/movie/{Slug<[a-zA-Z0-9_-]+>}", name="movie_read")
*/
```

```twig
{# <a href="{{ path('movie_read', {slug: movie.slug }) }}" class="fs-1 mt-3 text-danger align-self-start"> #}
<a href="{{ path('movie_read', {Slug: movie.slug }) }}" class="fs-1 mt-3 text-danger align-self-start">
```

Attention à la majuscule dans le nom du paramètre de la route

## Controller "App\Controller\MainController::read()" requires that you provide a value for the "$slug" argument. Either the argument is nullable and no null value has been provided, no default value has been provided or because there is a non optional argument after this one

```php
/**
* @Route("/movie/{Slug<[a-zA-Z0-9_-]+>}", name="movie_read")
*/
 public function read($slug){}
```

le nom du paramètre (S majuscule) ne correspond pas au nom de l'argument de ma function (s minuscule)

## Command class "App\Command\SluggifyCommand" is not correctly initialized. You probably forgot to call the parent constructor

je viens de rajouter un constructeur à ma commande.

Il y a du code a exécuté avant d'utiliser une commande, ce code est dans le constructeur du parent.

On va donc apeller ce constructeur.

```php
public function __construct()
{
    parent::__construct();
}
```

## A circular reference has been detected when serializing the object of class "App\Entity\Movie" (configured limit: 1)

on demande de serialiser movies->genres->movies->genres ....
il faut corriger/améliorer nos groupes de serialisation

## [Semantical Error] Annotation @Groups is not allowed to be declared on class App\Entity\Movie. You may only use this annotation on these code elements: PROPERTY, METHOD

c'est trop facile, on ne peut pas mettre de groupe sur une classe. désolé.

## Multiple non-persisted new entities were found through the given association graph:

Je suis en train de créer une entité via une API
J'ai mis des ID dans une propriété de type relation (Movie->genres)

```text
* A new entity was found through the relationship 'App\Entity\Movie#genres' that was not configured to cascade persist operations for entity: App\Entity\Genre@796. To solve this issue: Either explicitly call EntityManager#persist() on this unknown entity or configure cascade persist this association in the mapping for example @ManyToOne(..,cascade={"persist"}). If you cannot find out which entity causes the problem implement 'App\Entity\Genre#__toString()' to get a clue.
* A new entity was found through the relationship 'App\Entity\Movie#genres' that was not configured to cascade persist operations for entity: App\Entity\Genre@822. To solve this issue: Either explicitly call EntityManager#persist() on this unknown entity or configure cascade persist this association in the mapping for example @ManyToOne(..,cascade={"persist"}). If you cannot find out which entity causes the problem implement 'App\Entity\Genre#__toString()' to get a clue.
```

L'erreur me dit qu'il a compris que c'était des Objet Genre.
Par contre il me propose de mettre en place un persist en auto (cascade), mais ce n'est peut-être pas mon objectif.

Si je ne veux pas créer d'objet Genre en même temps que créer un objet Movie, je veux juste faire l'association avec une entité existante.

Cette opération de reconnaissance d'entité via leur ID c'est la denormalisation

On va créer une classe qui va faire cette opération pour nous : un denormalizer (cf cours)

## test qui ne finit pas sans message d'erreur

```bash
bin/phpunit --coverage-html ./tests/coverage/2022-11-22-13-00-00
PHPUnit 9.5.26 by Sebastian Bergmann and contributors.

Testing 
..Processus arrêté
```

en remontant la piste, on met des `dump(__METHOD__ . ':' . __LINE__ );` dans notre controller, dans nos subscriber, pour finir par voir que c'est dans twig que ça déconne.

```twig
<!-- {{ dump(app.request) }} -->
```

ce dump fait planter le test ... normal les dump c'est que pendant le dev, il faut les enlever ASAP
