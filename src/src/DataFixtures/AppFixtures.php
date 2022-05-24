<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Review;
use App\Entity\Season;
use DateTimeImmutable;
use App\Entity\Casting;
use App\Service\MySlugger;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OflixProvider;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // @link https://fakerphp.github.io/
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

        // On peut fixer le "seed" du générateur (et avoir toujours les mêmes données)
        $faker->seed(2022);

        // On instancie notre provider custom O'flix
        $oflixProvider = new OflixProvider();
        // On ajoute notre provider à Faker
        $faker->addProvider($oflixProvider);

        // Users
        $admin = new User();
        $admin->setEmail('admin@admin.com');
        $admin->setRoles(['ROLE_ADMIN']);
        $admin->setPassword('$2y$13$.PJiDK3kq2C4owW5RW6Z3ukzRc14TJZRPcMfXcCy9AyhhA9OMK3Li');
        $manager->persist($admin);

        // Attention $manager = le Manager de Doctrine :D
        $managerUser = new User();
        $managerUser->setEmail('manager@manager.com');
        $managerUser->setRoles(['ROLE_MANAGER']);
        $managerUser->setPassword('$2y$13$/U5OgXbXusW7abJveoqeyeTZZBDrq/Lzh8Gt1RXnEDbT2xJqbv3vi');
        // Attention $manager = le Manager de Doctrine :D
        $manager->persist($managerUser);

        $user = new User();
        $user->setEmail('user@user.com');
        $user->setRoles(['ROLE_USER']);
        $user->setPassword('$2y$13$ZqCHV23K0KMWmCxntdDlmOocuxuuSOXeT7nfKy2ZbE2vFC1VS3Q..');
        $manager->persist($user);

        // Les genres
        // créer les genres et les stocker (dans un tableau)
        // (on peut aller chercher les noms de genres dans le tableau)

        // Tableau vide pour nos genres
        $genresList = [];

        for ($i = 1; $i <= 20; $i++) {

            // Nouveau genre
            $genre = new Genre();
            $genre->setName($faker->unique()->movieGenre());

            // On l'ajoute à la liste pour usage ultérieur
            // Patch pour éviter les doublons
            $genresList[] = $genre;

            // On persiste
            $manager->persist($genre);
        }

        // Persons

        // Tableau pour nos persons
        $personsList = [];

        for ($i = 1; $i <= 100; $i++) {

            // Nouvelle Person
            $person = new Person();
            $person->setFirstname($faker->firstName());
            $person->setLastname($faker->lastName());

            // On l'ajoute à la liste pour usage ultérieur
            $personsList[] = $person;

            // On persiste
            $manager->persist($person);
        }

        // Les films
        for ($m = 1; $m <= 10; $m++) {

            $movie = new Movie();
            $movie->setSummary($faker->paragraph());
            $movie->setSynopsis($faker->text(300));
            // On a une chance sur 2 d'avoir un film
            $movie->setType($faker->randomElement(['Film', 'Série']));
            $movie->setTitle($faker->unique()->movieTitle());

            // Option Ecriture 1
            // On génère le slug à partir du titre
            // /!\ Code déplacé dans MovieSluggerListener

            // Option Ecriture 2
            // En décomposé
            // /!\ Code déplacé dans MovieSluggerListener

            $movie->setReleaseDate($faker->dateTimeBetween('-100 years'));
            // Entre 30 min et 263 minutes
            $movie->setDuration($faker->numberBetween(30, 263)); // En vrai c'est le film le plus long de l'histoire c'est L'Incendie du monastère du Lotus rouge qui dure 1620 minutes / 27h
            $movie->setPoster('https://picsum.photos/id/' . $faker->numberBetween(1, 100) . '/450/300');
            // 1 chiffre après la virgule, entre 1 et 5
            $movie->setRating($faker->randomFloat(1, 1, 5));

            // Seasons
            // On vérifie si l'entitéeMovie est une série ou pas
            if ($movie->getType() === 'Série') {
                // Si oui on crée une boucle for avec un numéro aléatoire dans la condition pour déterminer le nombre de saisons
                // mt_rand() ne sera exécuté qu'une fois en début de boucle
                for ($j = 1; $j <= mt_rand(3, 8); $j++) {
                    // On créé la nouvelle entitée Season
                    $season = new Season();
                    // On insert le numéro de la saison en cours $j
                    $season->setNumber($j);
                    // On insert un numéro d'épisode aléatoire
                    $season->setEpisodesNumber(mt_rand(6, 24));
                    // Puis on relie notre saison à notre série
                    $season->setMovie($movie);

                    // On persite la saison
                    $manager->persist($season);
                }
            }

            // On ajoute de 1 à 3 genres au hasard pour chaque film
            for ($g = 1; $g <= mt_rand(1, 3); $g++) {

                // Un genre au hasard entre 0 et la longueur du tableau - 1
                // on va chercher un index entre 0 et 19 (20 - 1)
                $randomGenre = $genresList[mt_rand(0, count($genresList) - 1)];
                $movie->addGenre($randomGenre);
            }

            // Avant de créer les castings
            // on mélange les valeurs du tableau $personsList
            // afin de piocher dedans les index 1, 2, 3, ... et ne pas avoir de doublon
            shuffle($personsList);

            // On ajoute de 3 à 5 castings par films au hasard pour chaque film
            for ($c = 1; $c <= mt_rand(3, 5); $c++) {

                $casting = new Casting();
                // Les propriétés role et creditOrder
                $casting->setRole($faker->name());
                $casting->setCreditOrder($c);

                // Les 2 associations
                // Movie => le film courant, celui de la boucle
                $casting->setMovie($movie);
                // Person
                // On pioche les index fixes 1, 2, 3, ...
                $randomPerson = $personsList[$c];
                $casting->setPerson($randomPerson);

                // On persiste
                $manager->persist($casting);
            }

            // @todo Reviews
            // Création des "Reviews"
            // On crée 15 à 20 "ratings" 
            for ($j = 0; $j < mt_rand(15, 20); $j++) {
                $review = new Review();

                $review
                    ->setRating(mt_rand(2, 5))
                    ->setUsername($faker->userName())
                    ->setEmail($faker->email())
                    ->setContent($faker->realTextBetween(100, 300))
                    ->setReactions($faker->randomElements([
                        'smile',
                        'cry',
                        'think',
                        'sleep',
                        'dream',
                    ], mt_rand(1, 4)))
                    // @option Voir le code de Vincent Mitry pour un provider custom
                    ->setWatchedAt(new DateTimeImmutable('-' . mt_rand(1, 50) . ' years'))
                    ->setMovie($movie);

                $manager->persist($review);
            }

            $manager->persist($movie);
        }

        $manager->flush();
    }
}
