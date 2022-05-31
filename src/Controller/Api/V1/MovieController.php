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
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Validator\ValidatorInterface;

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

        // /!\ Attention au JSON Hijacking !
        // On place notre tableau dans un tableau associatif
        // @link https://symfony.com/doc/current/components/http_foundation.html#creating-a-json-response
        return $this->json(['movies' => $moviesList], Response::HTTP_OK, [], ['groups' => 'movies_get_collection']);
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
        ManagerRegistry $doctrine,
        ValidatorInterface $validator
    ) {
        // On doit récupérer le contenu JSON qui se trouve dans la Request
        $jsonContent = $request->getContent();

        // Graĉe au Denormalizer ajouté dans src/Serializer
        // et en transmettant des ids sur les relations depius le JSON d'entrée
        // Par ex.
        // "genres": [
        //     1384,
        //     1402
        // ]
        // le denormalizer ira chercher les entités liées existantes dans la base
        // et fera le lien automatiquement avec $movie

        // @link https://symfony.com/doc/current/components/serializer.html#deserializing-an-object
        // On "désérialise" le contenu JSON en entité de type Movie
        $movie = $serializer->deserialize($jsonContent, Movie::class, 'json');

        // On valide l'entité
        // @link https://symfony.com/doc/current/validation.html#using-the-validator-service
        $errors = $validator->validate($movie);

        // 0 => Symfony\Component\Validator\ConstraintViolation {#979 ▼
        //     -message: "This value is already used."
        //     -messageTemplate: "This value is already used."
        //     -parameters: array:1 [▶]
        //     -plural: null
        //     -root: App\Entity\Movie {#891 ▶}
        //     -propertyPath: "title"
        //     -invalidValue: ""
        //     -constraint: Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity {#784 ▶}
        //     -code: "23bd9dbf-6b9b-41cd-a99e-4844bcf3077f"
        //     -cause: array:1 [▶]
        // }

        if (count($errors) > 0) {

            // On boucle sur le tableau d'erreurs
            // On crée un tableau pour retourner un JSON propre au client
            // Par ex.
            // $errors = [
            //     'title' => [
            //         'This value is already used.',
            //         'This value can not be blank.',
            //     ],
            //     'type' => [
            //         'This value can not be blank.',
            //     ]
            // ];

            $cleanErrors = [];

            /** @var ConstraintViolation $error */
            foreach ($errors as $error) {

                // On récupère les infos
                $property = $error->getPropertyPath(); // 'title'
                $message = $error->getMessage(); // 'This value is already used.'

                // On push tout ça dans un tableau à la clé $property

                // // On crée un tableau à la clé $property s'il n'existe pas déjà
                // if (!array_key_exists($property, $cleanErrors)) {
                //     $cleanErrors[$property] = [];
                // }
                // // On ajoute le message dedans
                // $cleanErrors[$property][] = $message;

                // OU plus court
                // On ajoute le message dans un tableau à la clé $property
                // PHP gère lui-même l'existence du second tableau
                $cleanErrors[$property][] = $message;
                // array_push($cleanErrors[$property], $message);
            }

            return $this->json($cleanErrors, Response::HTTP_UNPROCESSABLE_ENTITY);
            // On peut aussi retourner $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY)
            // Si on ne souhaite pas reformater la sortie
        }

        // On la sauvegarde en base
        $em = $doctrine->getManager();
        $em->persist($movie);
        $em->flush();

        // On retourne une réponse qui contient (REST !)
        // - un status code 201
        // - un en-tête (header) Location: URL_DE_LA_RESSOURCE_CREEE
        // - option perso : le JSON de l'entité créée
        return $this->json(
            // Le film créé
            $movie,
            // Le status code : 201 CREATED
            // utilisons les constantes de classe !
            Response::HTTP_CREATED,
            // REST demande un header Location + l'URL de la ressource créée
            // (un tableau clé-valeur)
            [
                'Location' => $this->generateUrl('api_v1_movies_get_item', ['id' => $movie->getId()])
            ],
            // Pour éviter les références circulaires
            ['groups' => 'movies_get_item']
        );
    }
}
