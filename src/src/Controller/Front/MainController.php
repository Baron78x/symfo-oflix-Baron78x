<?php

namespace App\Controller\Front;

use App\Model\Movies;
use App\Repository\MovieRepository;
use App\Service\FavoritesManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    /**
     * Affiche la page d'accueil
     * 
     * @Route("/", name="home", methods={"GET"})
     * 
     * @return Response
     */
    public function home(MovieRepository $movieRepository): Response
    {
        // On récupère les données depuis le Repository
        // => derniers films en premier
        $moviesList = $movieRepository->findAllOrderedByReleasedDateDQL();
        // dump($moviesList);

        return $this->render('front/main/home.html.twig', [
            'moviesList' => $moviesList,
        ]);
    }

    /**
     * Changement de thème
     * 
     * @Route("/theme/toggle", name="theme_toggle")
     */
    public function themeToggle(SessionInterface $session)
    {
        // On souhaite que le thème par défaut soit "netflix"
        // La valeur stockée est un booléen (actif/inactif)
        // Ici, on va inverser le booléen de true à false et vice-versa

        // Qu'a-t-on dans l'attribut de session "netflix_theme"
        // Si non définie, on veut netflx par défaut défault donc "true"
        $netflixActive = $session->get('netflix_theme', true);

        // On va inserver ce booléen via l'opérateur "not" !
        // Ceci crée le "toggle" (true => false et false => true)
        $netflixActive = !$netflixActive;

        // On sauvegarde la nouvelle valeur en session
        $session->set('netflix_theme', $netflixActive);

        // Test session/tableau
        $session->set('array_test', [
            'promo' => 'Boson Symfony',
            'year' => 2022,
            'students' => 17,
        ]);

        // On redirige vers la home
        // @todo bonus : rediriger vers la page d'origine
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/favorites", name="favorites", methods={"GET", "POST"})
     */
    public function favorites(Request $request, MovieRepository $movieRepository, FavoritesManager $favoritesManager): Response
    {
        if ($request->isMethod('POST')) {

            $movieId = $request->request->get('movie');
            $movie = $movieRepository->find($movieId);

            // Toggle ce film
            $added = $favoritesManager->toggle($movie);

            if ($added) {

                $this->addFlash(
                    'success',
                    $movie->getTitle() . ' -  a été ajouté de votre liste de favoris.'
                );

            } else {

                $this->addFlash(
                    'warning',
                    $movie->getTitle() . ' - a été retiré de votre liste de favoris.'
                );

            }

            return $this->redirectToRoute('favorites');
        }

        return $this->render('front/main/favorites.html.twig');
    }

    /**
     * @Route("/favorites/delete", name="delete_favorites", methods={"POST"})
     */
    public function deleteFavorites(FavoritesManager $favoritesManager): Response
    {
        // Vide la liste
        $success = $favoritesManager->empty();

        if ($success) {

            $this->addFlash(
                'success',
                'Liste de favoris vidée.'
            );

        } else {

            $this->addFlash(
                'warning',
                'La liste de favoris non vidée (contactez votre administrateur).'
            );

        }

        return $this->redirectToRoute('favorites');
    }
}