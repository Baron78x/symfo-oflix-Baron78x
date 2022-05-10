<?php

namespace App\Controller;

use DateTime;
use App\Entity\Genre;
use App\Entity\Movie;
use App\Entity\Season;
use App\Repository\MovieRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Expérimentation Doctrine
 */

class TestController extends AbstractController
{
    /**
     * @Route("/test/genre/add", name="test_genre_add")
     */
    public function addGenre()
    {
        // On crée un objet Genre
        $fantastique = new Genre();
        // On le définit
        $fantastique->setName('Fantastique');

        dd($fantastique);
    }

    /**
     * Browse : lister les films/séries
     * 
     * @Route("/test/movie/browse", name="movie_browse")
     */
    public function movieBrowse(MovieRepository $movieRepository)
    {
        // On va chercher tous les films depuis le Repository de Movie
        $moviesList = $movieRepository->findAll();

        dump($moviesList);

        // Pour accrocher ma toolbar :D
        return new Response('</body>');
    }

    /**
     * Read : afficher un film/série donnée
     * 
     * @Route("/test/movie/read/{id}", name="movie_read")
     */
    public function movieRead($id, MovieRepository $movieRepository)
    {
        // On va chercher tous les films depuis le Repository de Movie
        $movie = $movieRepository->find($id);

        // @todo : gérer la 404

        dump($movie);

        return $this->render('test/show.html.twig', [
            'movie' => $movie,
        ]);
    }

    /**
     * Edit : modifier un film/série donnée
     * 
     * @Route("/test/movie/edit/{id}", name="movie_edit")
     */
    public function movieEdit($id, ManagerRegistry $doctrine)
    {
        // 1. On récupère l'objet
        $movie = $doctrine->getRepository(Movie::class)->find($id);

        // @todo : 404 ?

        // 2. On le modifie (mode bac à sable !)
        $movie->setDuration(mt_rand(30, 180));

        // 3. On le sauvegarde
        $entityManager = $doctrine->getManager();
        // /!\ Pas de persist car entité déjà persistée, elle a un id
        // On flush direct
        $entityManager->flush();

        dd('Entité modifiée', $movie);
    }

    /**
     * Add : ajouter un film/série donnée
     * 
     * @Route("/test/movie/add", name="movie_add")
     */
    public function movieAdd(ManagerRegistry $doctrine)
    {
        // On crée un objet de type Movie
        $movie = new Movie();
        // On définit ses propriétés
        $movie->setTitle('Rambo Last Blood 2');
        // /!\ Doctrine souhiate un objet PHP DateTime
        $movie->setReleaseDate(new DateTime('2019-06-01'));
        $movie->setDuration(89);

        dump($movie);

        // On fait appel à l'EntityManager de Doctrine pour "persister" notre entité
        // Penser "grappin Toy Story"
        // @link : https://i.pinimg.com/236x/f2/c9/a8/f2c9a89f2bd904c75544fd5ccfcf737d.jpg
        $entityManager = $doctrine->getManager();
        // On lui demande de "persister" notre entité (de préparer la requête d'INSERT)
        $entityManager->persist($movie);
        // On demande d'éxécuter la (ou les) requêtes SQL associées
        $entityManager->flush();

        dump($movie);

        // Pour accrocher ma toolbar :D
        return new Response('</body>');
    }

    /**
     * Delete : supprimer un film/série donnée
     * 
     * @Route("/test/movie/delete/{id}", name="movie_delete")
     */
    public function movieDelete($id, ManagerRegistry $doctrine)
    {
        // 1. On récupère l'objet
        $movie = $doctrine->getRepository(Movie::class)->find($id);

        // @todo : 404 ?

        // 2. On le supprime
        $entityManager = $doctrine->getManager();
        $entityManager->remove($movie);
        $entityManager->flush();

        // L'enregistrement est supprimée mais l'objet PHP reste actif
        // jusqu'à la fin du script
        dd('Entité supprimée', $movie);
    }

    /**
     * Ajout d'une saison à film existant
     * 
     * @Route("/test/season/add", name="season_add")
     */
    public function seasonAdd(ManagerRegistry $doctrine)
    {
        // On va récupérer la série X-Files (id=4)
        $xFiles = $doctrine->getRepository(Movie::class)->find(4);

        // Créer une saison
        $season = new Season();
        // Saison 2
        $season->setNumber(2);
        // 25 épisodes
        $season->setEpisodesNumber(25);

        // L'associer à la série voulue
        // $season->setMovie($xFiles);
        $xFiles->addSeason($season);

        // Sauvegarder
        $entityManager = $doctrine->getManager();
        $entityManager->persist($season);
        $entityManager->flush();

        dd($season);
    }
}