<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Model\Movies;
use App\Repository\CastingRepository;
use App\Repository\MovieRepository;
use App\Repository\ReviewRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/list", name="movie_list")
     */
    public function list(MovieRepository $movieRepository): Response
    {
        // on récupère les données depuis le modèle
        // => par ordre alphabétique du titre
        $moviesList = $movieRepository->findAllOrderedByTitleAscQB();

        return $this->render('front/movie/list.html.twig', [
            'moviesList' => $moviesList,
        ]);
    }

    /**
     * @link https://symfony.com/doc/current/routing.html#parameters-validation
     * 
     * @Route("/movie/{id}", name="movie_show", requirements={"id"="\d+"})
     */
    public function show(Movie $movie = null, CastingRepository $castingRepository, ReviewRepository $reviewRepository)
    {
        // 404 ?
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        // On crée notre propre requête pour récupérer les castings
        // du film concerné
        $castingList = $castingRepository->findCastingOfMovieQB($movie);

        // On va chercher les critiques du film
        $reviewsList = $reviewRepository->findBy(
            ['movie' => $movie],
            ['id' => 'ASC']
        );

        return $this->render('front/movie/show.html.twig', [
            'movie' => $movie,
            'castingList' => $castingList,
            'reviewsList' => $reviewsList,
        ]);
    }
}
