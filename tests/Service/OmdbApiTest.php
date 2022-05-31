<?php

namespace App\Tests\Service;

use App\Service\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpClient\HttpClient;

class OmdbApiTest extends KernelTestCase
{
    // tester que fetch renvoie bien un tableau contenant 
    //  - une clef qui s'appelle Poster 
    //  - une clef qui s'appelle imdbID
    public function testFetch(): void
    {
        // $kernel = self::bootKernel();
        // out = object under test
        // getContainer lance le bootKernel pour nous si on ne l'a pas fait
        $out = static::getContainer()->get(OmdbApi::class);

        $movie = $out->fetch('the intouchables');
        $this->assertArrayHasKey('Poster', $movie);
        $this->assertArrayHasKey('imdbID', $movie);

        $movie = $out->fetch('intouchargaerbvaerbgables');

        $this->assertArrayHasKey('Response', $movie);
        $this->assertSame('False', $movie['Response']);
    }
    
    public function testFetchPoster(): void
    {
        // comme on a besoin du conteneur de service, on démarre le noyau de Symfony
        $kernel = self::bootKernel();

        // on vérifie que l'on est bien en env de test
        // c'est un test par défaut qui ne nous intéressera pas en vrai
        $this->assertSame('test', $kernel->getEnvironment());

        // on récupère le service à tester
        // $routerService = static::getContainer()->get('router');
        $omdbApi = static::getContainer()->get(OmdbApi::class);

        $poster = $omdbApi->fetchPoster('the intouchables');
        // vérifier que l'image est bien la bonne https://m.media-amazon.com/images/M/MV5BMTYxNDA3MDQwNl5BMl5BanBnXkFtZTcwNTU4Mzc1Nw@@._V1_SX300.jpg
        $this->assertSame('https://m.media-amazon.com/images/M/MV5BMTYxNDA3MDQwNl5BMl5BanBnXkFtZTcwNTU4Mzc1Nw@@._V1_SX300.jpg', $poster);

        $poster = $omdbApi->fetchPoster('intouchargaerbvaerbgables');
        // vérifier qu'on recoit bien la valeur null
        // $this->assertSame(null, $poster);
        $this->assertNull($poster);
    }

}
