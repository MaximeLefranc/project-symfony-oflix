# Bonus fixture

## faker cinema

[github](https://github.com/JulienRAVIA/FakerCinemaProviders)

```bash
composer require xylis/faker-cinema-providers
Info from https://repo.packagist.org: #StandWithUkraine
Using version ^2.0 for xylis/faker-cinema-providers
./composer.json has been updated
Running composer update xylis/faker-cinema-providers
Loading composer repositories with package information
Updating dependencies
Lock file operations: 1 install, 0 updates, 0 removals
  - Locking xylis/faker-cinema-providers (v2.0.1)
Writing lock file
Installing dependencies from lock file (including require-dev)
Package operations: 1 install, 0 updates, 0 removals
  - Downloading xylis/faker-cinema-providers (v2.0.1)
  - Installing xylis/faker-cinema-providers (v2.0.1): Extracting archive
Generating optimized autoload files
71 packages you are using are looking for funding.
Use the `composer fund` command to find out more!

Run composer recipes at any time to see the status of your Symfony recipes.

Executing script cache:clear [OK]
Executing script assets:install public [OK]

No security vulnerability advisories found
```

dans le fichier de fixture

```php
use Xylis\FakerCinema\Provider\Movie as MovieFaker;
use Xylis\FakerCinema\Provider\TvShow;

$faker->addProvider(new MovieFaker($faker));
$faker->addProvider(new TvShow($faker));

$movie->setTitle($faker->movie());
$movie->setTitle($faker->tvShow());
```
