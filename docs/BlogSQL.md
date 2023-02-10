# SQL

Post : code_Author
Comment : code_Post

## Je veux tout les commentaires des articles de l'auteur 1

```sql
SELECT *
FROM Post
INNER JOIN Comment ON Post.code = Comment.Code_Post
WHERE code_Author = 1
```

## Je veux les commentaires des Post dont les ID sont 1 OU 8 OU 93

```sql
SELECT *
FROM Comment
WHERE 
Code_Post = 1
OR code_post = 8
OR code_post = 93
```

même chose avec l'instruction [IN](https://sql.sh/cours/where/in)

```sql
SELECT *
FROM Comment
WHERE Code_Post IN (1, 8, 93,102)
```

## Je veux tout les commentaires dont l'auteur est 1

```sql
SELECT *
FROM Comment
WHERE Code_Post IN (
    SELECT code_post
    FROM Post
    WHERE code_Author = 1
    )
```

je récupère tout les ID des post dont l'auteur est 1 : 1,8,93,102

```sql
SELECT code_post
FROM Post
WHERE code_Author = 1
```
