<?php

namespace App\Controller\Front;

use App\Model\Movies;
use App\Repository\MovieRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

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
        dump($moviesList);

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
}