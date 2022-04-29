# Routes de l'application

| URL           | Méthode HTTP | Contrôleur       | Méthode     | Titre HTML                   | Commentaire                  |
| ------------- | ------------ | ---------------- | ----------- | ---------------------------- | ---------------------------- |
| `/`           | `GET`        | `MainController` | `home`      | Bienvenue sur O'flix         | Page d'accueil               |
| `/movie/{id}` | `GET`        | `MainController` | `movieShow` | O'flix - Titre du film/serie | Page détails d'un film/serie |
