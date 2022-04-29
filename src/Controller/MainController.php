<?php

namespace App\Controller;

use App\Model\Movies;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * Affiche la page d'accueil
     * 
     * @return Response
     * @Route("/", name="home", methods={"GET"})
     */
    public function home(): Response
    {
        // on récupère les données depuis le modèle
        $moviesModel = new Movies();
        $moviesList = $moviesModel->getAllMovies();

        return $this->render('main/home.html.twig', [
            'moviesList' => $moviesList
        ]);
    }

}