<?php

namespace App\Controller;

use App\Model\Movies;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/list", name="movie_list")
     */
    public function list(): Response
    {
        // on récupère les données depuis le modèle
        $moviesModel = new Movies();
        $moviesList = $moviesModel->getAllMovies();

        return $this->render('movie/list.html.twig', [
            'moviesList' => $moviesList,
        ]);
    }

    /**
     * @link https://symfony.com/doc/current/routing.html#parameters-validation
     * 
     * @Route("/movie/{id}", name="movie_show", requirements={"id"="\d+"})
     */
    public function show($id)
    {
        // on récupère la donnée depuis le modèle
        $moviesModel = new Movies();
        $movie = $moviesModel->getOneById($id);

        // 404 ?
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        return $this->render('movie/show.html.twig', [
            'movie' => $movie,
        ]);
    }
}
