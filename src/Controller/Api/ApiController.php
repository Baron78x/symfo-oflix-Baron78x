<?php

namespace App\Controller\Api;

use App\Model\Movies;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/movies", name="app_api", methods={"GET"})
     */
    public function moviesCollection(): Response
    {
        $moviesModel = new Movies();
        $moviesList = $moviesModel->getAllMovies();

        return $this->json($moviesList);
    }
}
