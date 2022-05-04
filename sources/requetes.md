# Requêtes SQL pour le projet

## Requêtes pour les pages

Récupérer tous les films.

```sql
SELECT * FROM `movie`
```

Récupérer les acteurs et leur(s) rôle(s) pour un film donné.

```sql
SELECT * FROM `casting` INNER JOIN `person` ON `person_id` = `person`.`id` WHERE `movie_id` =4
```

Récupérer les genres associés à un film donné.

SELECT * 
FROM `genre`
INNER JOIN `movie_genre`
ON `genre`.`id` = `movie_genre`.`genre_id`
WHERE `movie_id`=1

Récupérer les saisons associées à un film/série donné.

SELECT * FROM `season`
INNER JOIN `movie` ON `movie`.`id` = `season`.`movie_id`
WHERE `movie_id` = 1

Récupérer les critiques pour un film donné.

SELECT *
FROM `review`
WHERE `movie_id` = 1
ORDER BY `published_date` DESC

Récupérer les critiques pour un film donné, ainsi que le nom de l'utilisateur associé.

SELECT *
FROM `review`
INNER JOIN `user` ON `review`.`user_id` = `user`.`id`
WHERE `movie_id` = 1

Calculer, pour chaque film, la moyenne des critiques par film (en une seule requête).
SELECT AVG(`rating`)
FROM `review`

Pour avec les noms de films
SELECT `movie`.`title`, AVG(`review`.`rating`)
FROM `review`
LEFT OUTER JOIN `movie` ON `movie`.`id` = `review`.`movie_id`
GROUP BY `title`


Idem pour un film donné.
SELECT AVG(`rating`)
FROM `review`
WHERE `movie_id` = 2

## Requêtes de recherche

Récupérer tous les films pour une année de sortie donnée.

SELECT *
FROM `movie`
WHERE YEAR(`release_date`) = 2010

SELECT * FROM movie
WHERE `release_date` LIKE '2011%'

Récupérer tous les films dont le titre est fourni (titre complet).

SELECT *
FROM `movie`
WHERE `title` IS NOT NULL

Récupérer tous les films dont le titre contient une chaîne donnée.

SELECT *
FROM `movie`
WHERE `title` LIKE '%the%'

## Bonus : Pagination
Nombre de films par page : 10 (par ex.)

SELECT * FROM `movie` LIMIT 10 OFFSET 10;

Récupérer la liste des films de la page 2 (grâce à LIMIT).
Testez la requête en faisant varier le nombre de films par page et le numéro de page.
