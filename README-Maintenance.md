# Maintenance Subscriber

Il existe des Events dans le Kernel de Symfony, déclenchés à différentes étapes.

Nous allons jouer avec l'événement `kernel.response`. Il s'agit de l'événement qui se déclenche juste avant d'envoyer la `Response` au client.

C'est-à-dire qu'on peut intercepter l'objet `Response` de tous les contrôleurs. L'objet `Response` contient une propriété avec le HTML généré par Twig. On peut modifier ce HTML !

## Étape 1

On a vu qu'on pouvait utiliser des événements de formulaires ou de Doctrine de différentes façons. Tous les types d'événements en Symfony peuvent être manipulés avec un subscriber, une classe qui _s'abonne_ à un événement.

Vous allez créer un _subscriber_ pour l'événement `kernel.response`.  
Utilisez la commande `make:subscriber` et appelez le subscriber comme vous voulez, "Maintenance" serait un nom approprié.  
Le _maker_ vous demande à quel événement associer votre subscriber, tapez _kernel.response_.

## Étape 2

Votre subscriber est dans le dossier `/src/EventSubscriber`, il est prêt à être modifié. Tout ce que vous faites dedans sera modifié à chaque requête dans le projet, qu'importe la route !

À l'intérieur de la méthode qui n'attend que vous, vous recevez un objet `ResponseEvent`. La méthode `getResponse()` sur cet objet vous donne un objet `Response`. Trouvez le moyen d'obtenir le contenu de la Response (vous pouvez fouillez la documentation ou vous contenter de `dump()`).

## Ètape 3

Modifiez le contenu de la réponse. Tout le HTML qui sera envoyé est une longue chaine de caractères !

En utilisant `str_replace()`, remplacez la balise `<body>` par `<body><div class="alert alert-danger">Maintenance prévue mardi 10 janvier à 17h00</div>` dans cette chaine de caractère.

## Étape 4

Affectez le nouveau contenu à l'objet `Response` via la méthode appropriée.

Ça y est, vous avez altéré le code HTML qui sera envoyé. Vous annoncez désormais sur toutes les pages qu'une maintenance aura lieu sur le site le 10 janvier !

## Bonus

Et si le message était optionnel ? Et si on pouvait l'activer juste avec une variable d'environnement dans le fichier `.env.local` ?

Trouvez un moyen d'utiliser une variable d'environnement dans votre subscriber pour activer ou désactiver votre modification plus facilement :tada: