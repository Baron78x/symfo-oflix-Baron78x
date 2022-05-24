<?php

namespace App\Controller\Api\V1;

use App\Entity\Movie;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Classe qui s'occupe des ressources de type Movie
 * 
 * @Route("/api/v1", name="api_v1_")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/movies", name="movies_get_collection", methods={"GET"})
     */
    public function moviesGetCollection(MovieRepository $movieRepository): Response
    {
        $moviesList = $movieRepository->findAll();

        return $this->json($moviesList, Response::HTTP_OK, [], ['groups' => 'movies_get_collection']);
    }

    /**
     * @Route("/movies/{id}", name="movies_get_item", methods={"GET"}, requirements={"id"="\d+"})
     */
    public function moviesGetItem(Movie $movie = null)
    {
        // 404 ?
        if ($movie === null) {
            // Voir si meilleure solution ici : https://symfony.com/doc/current/controller/error_pages.html#overriding-error-output-for-non-html-formats
            return $this->json(['error' => 'Film non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        return $this->json($movie, Response::HTTP_OK, [], ['groups' => 'movies_get_item']);
    }

    /**
     * @Route("/movies", name="movies_post", methods={"POST"})
     */
    public function moviesPost(
        Request $request,
        SerializerInterface $serializer,
        ManagerRegistry $doctrine
    ) {
        // On doit récupérer le contenu JSON qui se trouve dans la Request
        $jsonContent = $request->getContent();
        
        // @link https://symfony.com/doc/current/components/serializer.html#deserializing-an-object
        // On "désérialise" le contenu JSON en entité de type Movie
        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');

        // On valide l'entité
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        

        // On la sauvegarde en base
        $em = $doctrine->getManager();
        $em->persist($movie);
        $em->flush();

        // On retourne une réponse qui contient (REST !)
        // - un status code 201
        // - un en-tête (header) Location: URL_DE_LA_RESSOURCE_CREEE
        // - option perso : le JSON de l'entité créée
    }
}
