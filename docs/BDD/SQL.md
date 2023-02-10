# SQL

## Récupérer tous les films

```sql
SELECT * 
FROM movie
```

## Récupérer les acteurs et leur(s) rôle(s) pour un film donné (1)

```sql
SELECT person.firstname, person.lastname, casting.role
FROM casting
INNER JOIN person ON casting.person_id = person.id
WHERE movie_id = 1
```

```text
SELECT table1.champ1, table2.champs3
FROM table1
INNER JOIN table2 ON table1.champs2 = table2.champs1
WHERE table1.champs3
-- ORDER BY ....
-- LIMIT 5
```

je le fait en 3 étapes :

* je pars d'une table, je fait le where si besoin
* je fait la jointure vers une autre table
* je précise les champs que je veux

## Récupérer les genres associés à un film donné

```sql
SELECT genre.name AS genreName
FROM movie_genre
INNER JOIN genre ON movie_genre.genre_id = genre.id
WHERE movie_id = 2
```

## Récupérer les saisons associées à un film/série donné

[doc concat()](https://sql.sh/fonctions/concat)

```sql
SELECT CONCAT('Saison ', number) AS nom_saison
FROM season
WHERE movie_id = 3
ORDER BY number ASC
```

## Récupérer les critiques pour un film donné, ainsi que le nom de l'utilisateur associé

```sql
SELECT user.nickname,  review.rating, review.content
FROM review
INNER JOIN user ON review.user_id = user.id
WHERE movie_id = 4
```

## a moyenne des critiques par film pour un film donné

[doc sql.sh](https://sql.sh/fonctions/agregation/avg)

```sql
SELECT AVG(review.rating) as moyenne_rating
FROM review
WHERE movie_id = 4
```

[doc sql.sh](https://sql.sh/cours/group-by)

```sql
SELECT movie_id , AVG(review.rating) as moyenne_rating
FROM review
GROUP BY movie_id
```

avec jointure

```sql
SELECT movie.title , AVG(review.rating) as moyenne_rating
FROM review
INNER JOIN movie ON review.movie_id = movie.id
GROUP BY movie_id
```
