# brief

serie/film <-> genre
serie/film -> saison (avec nb episode) pas entité épisode
    que pour les séries : cardinalité mini à 0
serie/film <-> acteur ⚠️ rôles !!

utilisateur (rôle : admin) <-critique- serie/film

## dico de données

pas toujours fait, car on part de suite vers le MCD.
Pour des petits projets (3-4 entités), on peut le faire à postériori.

| Entité | propriété    | spécificités     | Commentaire    |
| ------ | ------------ | ---------------- | -------------- |
| série/film | type | film ou serie | type de format |
| série/film | duration | en minutes | durée en minutes |
| série/film | poster | url | url vers une image externe |
| série/film | rating | de 0 à 5, avec une décimale | moyenne/médiane des critiques des utilisateurs |
| genre | nom | ---- |  |
| saison | nb_episode | entier | nombre d'épisodes de la saison |
| saison | numero | entier | le numéro de la saison, presque le nom |
| acteur | nom |  |  |
| acteur | prénom |  |  |
| ?? | rôle |  |  |
| user | login | email |  |
| user | mdp |  |  |
| user | username |  | pour l'affichage devant une critique |
| user | role |  | pour les droits |
| critique | contenu |  |  |
| critique | rating | 0..5 |  |

## MCD - MLD - MPD

Méthode Merise

[schema](https://whimsical.com/mcd-mld-mpd-CWTY9sWne6P4zWXPxr6gqS)

### Modèle Conceptuel de Donnée

Objectifs :

* identifier les relations entre les entités : un verbe
* poser des cardinalités entre les entités : min, max (min : 0/1, max: 1/N)
* valider la structure avec des non-tech (client/moldus)

[outils en ligne](https://www.mocodo.net/)

par défaut, une cardinalité min est à 0, c'est moins restrictif que 1

```text
actor: code

Acting in, 0N movie, 0N actor: role

user: code
published, 0N user, 11 reviews
reviews: code
written on, 0N movie, 11 reviews
movie: code
belongs to, 1N movie, 0N genre
genre: code

has, 0N movie, 11 season
season: code

```

### Modèle Logique de Donnée

Objectifs :

* créer les clés étrangères (FK)
* créer les tables pivot
* traduire le MCD en MPD, étape transitoire

il n'est pas nécessaire de faire un schéma de type MCD

#### Cardinalités : Comment positionner les FK ?

J'ai deux tables :

* Movie
* Season

Il y a une relation 0,N de `Movie` vers `Season`
Il y a une relation 1,1 de `Season` vers `Movie`

Un moyen mnémotechnique de me souvenir où va être la clé étrangère :
Les clés primaires voyagent ✈ et elles veulent aller dans le plus d'autres pays (table) possible.
Donc les clés primaires voyagent depuis la table avec la cardinalité maximum N vers l'autre table.
La clé primaire de `Movie` voyage donc vers la table `Season`, et devient une clé étrangère dans cette table.

Mais comment on fait quand il y a une cardinalité max N de chaque coté 🤔 ?

Repronnons l'idée du voyage :

les clés primaires voyagent, se rencontrent sur le chemin, tombent amoureuses 💘, et fondent une nouvelle table. Awwwn 🤗

On appellera ça une relation ManyToMany 😉

### Modèle Physique de Donnée

Objectifs :

* définir les types de donnée, propre à la BDD
* définir les nom des champs de la BDD (adieu code, bonjour id)
* préparer les scripts de création de BDD
