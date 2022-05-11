<?php

namespace App\DataFixtures;

use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Person;
use App\Entity\Season;
use DateTimeImmutable;
use App\Entity\Casting;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\DataFixtures\Provider\OflixProvider;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        

        // Les films

        // $shows = [
        //     1 =>[
        //         'type' => 'Film',
        //         'title' => 'A Bug\'s Life',
        //         'release_date' => '1998-01-01',
        //         'duration' => 93,
        //         'summary' => 'Tilt, fourmi quelque peu tête en l\'air, détruit par inadvertance la récolte de la saison.',
        //         'synopsis' => 'Tilt, fourmi quelque peu tête en l\'air, détruit par inadvertance la récolte de la saison. La fourmilière est dans tous ses états. En effet cette bévue va rendre fou de rage le Borgne, méchant insecte qui chaque été fait main basse sur une partie de la récolte avec sa bande de sauterelles racketteuses.
                
        //         Tilt décide de quitter l\'île pour recruter des mercenaires capables de chasser le Borgne.',
        //         'poster' => 'https://m.media-amazon.com/images/M/MV5BNThmZGY4NzgtMTM4OC00NzNkLWEwNmEtMjdhMGY5YTc1NDE4XkEyXkFqcGdeQXVyMTQxNzMzNDI@._V1_SX300.jpg',
        //         'rating' => 3.8
        //     ],
        //     2 =>[
        //         'type' => 'Série',
        //         'title' => 'Game of Thrones',
        //         'release_date' => '2011-01-01',
        //         'duration' => 52,
        //         'summary' => 'Neuf familles nobles se battent pour le contrôle des terres de Westeros, tandis qu\'un ancien ennemi revient...',
        //         'synopsis' => 'Il y a très longtemps, à une époque oubliée, une force a détruit l\'équilibre des saisons. Dans un pays où l\'été peut durer plusieurs années et l\'hiver toute une vie, des forces sinistres et surnaturelles se pressent aux portes du Royaume des Sept Couronnes. La confrérie de la Garde de Nuit, protégeant le Royaume de toute créature pouvant provenir d\'au-delà du Mur protecteur, n\'a plus les ressources nécessaires pour assurer la sécurité de tous. Après un été de dix années, un hiver rigoureux s\'abat sur le Royaume avec la promesse d\'un avenir des plus sombres. Pendant ce temps, complots et rivalités se jouent sur le continent pour s\'emparer du Trône de Fer, le symbole du pouvoir absolu.',
        //         'poster' => 'https://m.media-amazon.com/images/M/MV5BYTRiNDQwYzAtMzVlZS00NTI5LWJjYjUtMzkwNTUzMWMxZTllXkEyXkFqcGdeQXVyNDIzMzcwNjc@._V1_SX300.jpg',
        //         'rating' => 4.7
        //     ],
        //     3 =>[
        //         'type' => 'Film',
        //         'title' => 'Aline',
        //         'release_date' => '2021-01-01',
        //         'duration' => 126,
        //         'summary' => 'Québec, fin des années 60, Sylvette et Anglomard accueillent leur 14ème enfant : Aline. On lui découvre un don, elle a une voix en or.',
        //         'synopsis' => 'Québec, fin des années 60, Sylvette et Anglomard accueillent leur 14ème enfant : Aline. Dans la famille Dieu, la musique est reine et quand Aline grandit on lui découvre un don, elle a une voix en or. Lorsqu’il entend cette voix, le producteur de musique Guy-Claude n’a plus qu’une idée en tête… faire d’Aline la plus grande chanteuse au monde. Epaulée par sa famille et guidée par l’expérience puis l’amour naissant de Guy-Claude, ils vont ensemble écrire les pages d’un destin hors du commun.',
        //         'poster' => 'https://m.media-amazon.com/images/M/MV5BNjUxYTQ3YzItNjU5Ny00ZGM0LWJkMGUtN2FkMWRiNjFlY2ExXkEyXkFqcGdeQXVyMzcwMzExMA@@._V1_SX300.jpg',
        //         'rating' => 4.0
        //     ],
        //     4 =>[
        //         'type' => 'Série',
        //         'title' => 'Stranger Things',
        //         'release_date' => '2016-01-01',
        //         'duration' => 50,
        //         'summary' => '1983, à Hawkins dans l\'Indiana. Après la disparition d\'un garçon de 12 ans dans des circonstances mystérieuses, la petite ville du Midwest est témoin d\'étranges phénomènes.',
        //         'synopsis' => 'A Hawkins, en 1983 dans l\'Indiana. Lorsque Will Byers disparaît de son domicile, ses amis se lancent dans une recherche semée d’embûches pour le retrouver. Dans leur quête de réponses, les garçons rencontrent une étrange jeune fille en fuite. Les garçons se lient d\'amitié avec la demoiselle tatouée du chiffre "11" sur son poignet et au crâne rasé et découvrent petit à petit les détails sur son inquiétante situation. Elle est peut-être la clé de tous les mystères qui se cachent dans cette petite ville en apparence tranquille…',
        //         'poster' => 'https://m.media-amazon.com/images/M/MV5BN2ZmYjg1YmItNWQ4OC00YWM0LWE0ZDktYThjOTZiZjhhN2Q2XkEyXkFqcGdeQXVyNjgxNTQ3Mjk@._V1_SX300.jpg',
        //         'rating' => 4.2
        //     ]
        //     ];
        // foreach ($shows as $key => $value) {

            
            
        // $movie = new Movie();
        // $movie->setType($value['type']);
        // $movie->setTitle($value['title']);
        // $movie->setSummary($value['summary']);
        // $movie->setDuration($value['duration']);
        // $movie->setPoster($value['poster']);
        // $movie->setRating($value['rating']);
        // $movie->setSynopsis($value['synopsis']);
        // $movie->setReleaseDate(new DateTimeImmutable($value['release_date']));
            
        // $manager->persist($movie);
        // }


        // @link https://fakerphp.github.io/
        // use the factory to create a Faker\Generator instance
        $faker = Faker\Factory::create('fr_FR');

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

        $manager->flush();
    }
}
