<?php

namespace App\Controller\Api\V1;

use App\Entity\Genre;
use App\Repository\GenreRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Classe qui s'occupe des ressources de type Genre
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class GenreController extends AbstractController
{
    /**
     * @Route("/genres", name="get_genres_collection", methods={"GET"})
     */
    public function genresGetCollection(GenreRepository $genreRepository)
    {
        $genresList = $genreRepository->findAll();

        return $this->json(['genres' => $genresList], Response::HTTP_OK, [], ['groups' => 'genres_get_collection']);
    }

    /**
     * @Route("/genres/{id}/movies", name="genres_get_movies_collection", methods={"GET"})
     */
    public function moviesCollectionByGenre(Genre $genre = null): Response
    {
        // 404 ?
        if ($genre === null) {
            return $this->json(['error' => 'Non non non !'], Response::HTTP_NOT_FOUND);
        }

        // On affiche le genre avec ses films sassociés
        // via les groupes de sérialisation
        return $this->json(
            $genre,
            Response::HTTP_OK,
            [],
            // On fait appel au groupe 'genres_get_movies_collection' qui fait le lien avec Movie
            // On fait appel au groupe 'movies_get_collection' qui n'a pas de relation avec Genre
            ['groups' => ['genres_get_movies_collection', 'movies_get_collection']]);
    }
}
