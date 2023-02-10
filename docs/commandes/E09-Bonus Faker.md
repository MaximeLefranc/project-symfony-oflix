# E09-Bonus faker

## faker php

[site officiel](https://fakerphp.github.io/)

```bash
composer require fakerphp/faker

Info from https://repo.packagist.org: #StandWithUkraine
Using version ^1.20 for fakerphp/faker
./composer.json has been updated
Running composer update fakerphp/faker
Loading composer repositories with package information
Updating dependencies
Lock file operations: 1 install, 0 updates, 0 removals
  - Locking fakerphp/faker (v1.20.0)
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 1 install, 0 updates, 0 removals
  - Installing fakerphp/faker (v1.20.0): Extracting archive
Generating optimized autoload files
56 packages you are using are looking for funding.
Use the `composer fund` command to find out more!

Run composer recipes at any time to see the status of your Symfony recipes.

Executing script cache:clear [OK]
Executing script assets:install public [OK]

No security vulnerability advisories found
```

## utilisation dans une fixture

On crée une instance de Faker via une méthode statique : c'est un [singleton](https://refactoring.guru/fr/design-patterns/singleton)

[utiliser la version Française](https://fakerphp.github.io/locales/fr_FR/)

```php
use Faker\Factory;

$faker = Factory::create('fr_FR');

$faker->firstName();

$faker->numberBetween(30, 263);
```

[on ajoute une image par défaut pour le poster](https://amc-theatres-res.cloudinary.com/amc-cdn/static/images/fallbacks/DefaultOneSheetPoster.jpg)

## provider for faker

[doc](https://fakerphp.github.io/#faker-internals-understanding-providers)
