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
     * @Route("/movie/{slug}", name="movie_show")
     * 
     * Note : attention au SEO, à ne pas "casser" le référencement existant
     * pour cela on peut faire un mix id + slug, comme sur le blog O'clock
     * 
     * Ancienne URL
     * https://oclock.io/blog/6402/pierre-quand-la-neuroatypie-ouvre-la-porte-du-developpement-web
     * Nouvelle URL
     * https://oclock.io/blog/6402/pierre-quand-la-neuroatypie-ouvre-la-porte-du-garage
     * 
     * => le SEO est maintenu, côté dev on fera une redirection (302, Google mettra à jour son indexation)
     * de l'ancienne URL vers la nouvelle, si le slug n'est pas le bon.
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
