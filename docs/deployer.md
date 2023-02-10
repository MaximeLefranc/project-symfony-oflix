# deployer

## inclure le recipe de Symfony

Pour avoir des raccourcis, des task pré-faites, on va inclure le recipe fournit par deployer

⚠️ ce fichier n'est pas disponible/inclus/visible dans le projet, il est compris avec le fichier `deployer.phar`

[lien github](https://github.com/deployphp/deployer/blob/master/recipe/symfony.php)

```php
require 'recipe/symfony.php';
```

cela nous rajoute des variables pratiques :

```php
set('bin/console', '{{bin/php}} {{release_or_current_path}}/bin/console');
```

on pourra donc directement utiliser `{{bin/console}}` dans nos task.

et aussi, par le jeu des inclusions, des tasks :

[lien github common recipe](https://github.com/deployphp/deployer/blob/master/recipe/deploy/vendors.php)

cette tâche fait un `composer install`

```php
task('deploy:vendors')
```

### les options de composer

dans le fichier [vendors.php](https://github.com/deployphp/deployer/blob/master/recipe/deploy/vendors.php), il est définit `composer_options`

```php
set('composer_options', '--verbose --prefer-dist --no-progress --no-interaction --no-dev --optimize-autoloader');
```

on remarque le `--no-dev` qui dit que les packages `dev` ne seront jamais installés.

Si vous voulez utiliser de packages de dev, comme les fixtures, il faut changer cette variable dans notre fichier deploy

```php
set('composer_options', '--verbose --prefer-dist --no-progress --no-interaction --optimize-autoloader');
```

## créer des tasks utiles

commande doctrine pour créer la BDD

```php
task('init:database', function() {
    run('{{bin/console}} doctrine:database:create');
});
```

commande doctrine pour drop la BDD avec les options :

* seulement si elle existe
* pas de message d'interaction, répond toujours avec les réponses pas défaut

```php
task('init:database:drop', function() {
    run('{{bin/console}} doctrine:database:drop --if-exists --no-interaction --force');
});
```

commande que nous avons développé

```php
task('kustom:command:poster', function(){
    run('{{bin/console}} app:movies:poster');
});
```

## Utiliser les fixtures

⚠️ Dans la vraie vie on n'utilise pas les fixtures en PROD

Pour utiliser les fixtures, il faut être en `APP_ENV=dev` dans le fichier `.env.local` qui est dans le dossier `shared`

Pour cela on peut modifier le fichier en direct avec la commande linux `echo` et l'option `>`

[explain shell](https://explainshell.com/explain?cmd=echo+%22APP_ENV=dev%22+%3E+.env.local)

Comme nous avons plusieurs lignes à écrire, et que l'option `>` écrase le fichier, il faut utiliser une autre option pour ajouter nos lignes : `>>`

[explain shell](https://explainshell.com/explain?cmd=echo+%22APP_ENV%3Ddev%22+%3E%3E+.env.local)

On va donc en profiter pour écrire dans ce fichier la chaine de connexion et notre `OMDB_API_KEY`

Pour cela on va créer deux variables

```php
set("env_database", "mysql://explorateur:Ereul9Aeng@127.0.0.1:3306/oflix-simple?serverVersion=mariadb-10.3.34");
set("env_omdbapikey", "a93b767b");
```

ensuite on crée deux tasks, une pour écrire le fichier en mode PROD, et l'autre en DEV

```php
task('init:config:write:prod', function() {
    run('echo "APP_ENV=prod" > {{deploy_path}}/shared/.env.local');
    run('echo "DATABASE_URL={{env_database}}" >> {{deploy_path}}/shared/.env.local');
    run('echo "OMDB_API_KEY={{env_omdbapikey}}" >> {{deploy_path}}/shared/.env.local');
});

task('init:config:write:dev', function() {
    run('echo "APP_ENV=dev" > {{deploy_path}}/shared/.env.local');
    run('echo "DATABASE_URL={{env_database}}" >> {{deploy_path}}/shared/.env.local');
    run('echo "OMDB_API_KEY={{env_omdbapikey}}" >> {{deploy_path}}/shared/.env.local');
});
```

```bash
desc("Création des fixtures");
task('init:fixtures', function () {
    // comme la commande fixture nous pose la question si OUI ou NON on vide la base de données
    // et que l'on ne peut pas intéragir, on ajoute un "yes | " pour pré-répondre à la question
    run('yes | {{bin/console}} doctrine:fixtures:load');
});
```

## Faire une task pour le premier deploiement

```php
task('first_deploy')
```

dedans on y trouve dans l'ordre :

* `deploy:prepare` qui est un regroupement de [plusieures commandes](https://deployer.org/docs/7.x/recipe/common#deployprepare)
* `init:config:write:dev` notre task pour écrire la config de dev
* `deploy:vendors` on lance [composer install](https://deployer.org/docs/7.x/recipe/deploy/vendors#deployvendors)
* `deploy:cache:clear` on lance la commande [cache:clear](https://deployer.org/docs/7.x/recipe/symfony#deploycacheclear)
* `init:database:drop` au cas où on drop la DB
* `init:database` on créer la DB
* `database:migrate` on lance [les migrations](https://deployer.org/docs/7.x/recipe/symfony#databasemigrate)
* `init:fixtures` notre commande pour les fixtures
* `kustom:command:poster` la commande de récupération des posters
* `deploy:publish` [groupe de commande](https://deployer.org/docs/7.x/recipe/common#deploypublish)

## on se lance à l'eau

```bash
dep first_deploy prod -f deploy.php
```

### FAIL

si notre deploy a FAIL, on relance mais ...

```bash
task deploy:lock
[prod]  Deployer\Exception\GracefulShutdownException  in lock.php on line 14:
[prod]
[prod]   Deploy locked by JB-oclock.
[prod]   Execute "deploy:unlock" task to unlock.
[prod]
```

comme notre deploiement n'a pas été jusuqu'au bout, il n'a pas pu dé-lock
Il est gentil il nous donne la commande pour ça.

```bash
dep deploy:unlock prod -f deploy.php
```

### utilisation unitaire des tasks

comme on l'a vue avec le `deploy:unlock` on peut lancer chacune de nos tâches unitairement.

```bash
dep init:config:write:prod prod -f deploy.php
```

### Erreur ACL

```bash
Can't set writable dirs with ACL.
[prod]   Install ACL with next command:
[prod]   dep run 'sudo apt-get install acl' -- prod
```

l'éxécution de la commande `dep run 'sudo apt-get install acl'` depuis notre poste de dev va faire l'installation du paquet ACL sur notre serveur et permettre à deployer de rendre des dossiers `writable`
