<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Season;
use DateTimeImmutable;
use App\Entity\Casting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OflixProvider;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    private $slugger;
    
    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

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
            $movie->setReleaseDate($faker->dateTimeBetween('-100 years'));
            // Entre 30 min et 263 minutes
            $movie->setDuration($faker->numberBetween(30, 263)); // En vrai c'est le film le plus long de l'histoire c'est L'Incendie du monastère du Lotus rouge qui dure 1620 minutes / 27h
            $movie->setPoster('https://picsum.photos/id/'. $faker->numberBetween(1, 100).'/450/300');
            // 1 chiffre après la virgule, entre 1 et 5
            $movie->setRating($faker->randomFloat(1, 1, 5));
            $movie->setSlug($this->slugger->slug($movie->getTitle())->lower());

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

            $manager->persist($movie);
        }

        // New User
        // User User
        $newUser[0] = new User();
        $newUser[0]->setEmail('user@user.com');
        $newUser[0]->setPassword('$2y$13$Sgh47rZbh3FIfrGldQbB1.I1JtQt5fl.GiqgNTs7H3ICsCtzXPnY2');
        $newUser[0]->setRoles(['ROLE_USER']);
        $manager->persist($newUser[0]);
        // User Manager
        $newUser[1] = new User();
        $newUser[1]->setEmail('manager@manager.com');
        $newUser[1]->setPassword('$2y$13$USCmf9uqlyhK6..uslk.ouRC4AWmR.0FwwLxjoJ9GrlmJMIcZPeMe');
        $newUser[1]->setRoles(['ROLE_MANAGER']);
        $manager->persist($newUser[1]);
        // User Admin
        $newUser[2] = new User();
        $newUser[2]->setEmail('admin@admin.com');
        $newUser[2]->setPassword('$2y$13$OUheoo1RpoRNJATca7IXV.4FricZLgUfMCfO995ruXgSHd6u5Gx.e');
        $newUser[2]->setRoles(['ROLE_ADMIN']);
        $manager->persist($newUser[2]);

        $manager->flush();
    }
}