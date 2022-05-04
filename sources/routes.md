# Routes de l'application

| URL                 | Méthode HTTP | Contrôleur            | Méthode            | Titre HTML                          | Commentaire                           |
| ------------------- | ------------ | --------------------- | ------------------ | ----------------------------------- | ------------------------------------- |
| `/`                 | `GET`        | `MainController`      | `home`             | Bienvenue sur O'flix                | Page d'accueil                        |
| `/movie/{id}`       | `GET`        | `MovieController`     | `show`             | Titre du film/série                 | Page détails d'un film/série          |
| `/movie/list`       | `GET`        | `MovieController`     | `list`             | Liste des films et séries           | -                                     |
| `/movie/genre/{id}` | `GET`        | `MovieController`     | `listByGenre`      | Liste des films et séries par genre | -                                     |
| `/movie/search`     | `GET`        | `MovieController`     | `search`           | Recheche films et séries            | Sur le titre ou autre champ à définir |
| `/favorites`        | `GET`        | `FavoritesController` | `list`             | Liste des favoris                   | -                                     |
| `/favorites/add`    | `POST`       | `FavoritesController` | `add`              | Ajouter aux favoris                 | -                                     |
| `/favorites/remove` | `POST`       | `FavoritesController` | `remove`           | Supression d'un favoris             | -                                     |
| `/api/movies`       | `GET`        | `ApiController`       | `moviesCollection` | Liste des films au format JSON      | -                                     |
