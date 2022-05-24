<?php

namespace App\Controller\Back;

use App\Entity\Movie;
use App\Entity\Season;
use App\Form\SeasonType;
use App\Repository\SeasonRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * Classe qui gère le CRUD sur Season
 * 
 * Ceci est un préfixe de route pour les méthodes du contrôleur
 * @Route("/back/season")
 */
class SeasonController extends AbstractController
{
    /**
     * Les saisons de la série donnée
     * 
     * @Route("/movie/{id}", name="back_season_index", methods={"GET"})
     */
    public function index(Movie $movie, SeasonRepository $seasonRepository): Response
    {
        return $this->render('back/season/index.html.twig', [
            'seasons' => $seasonRepository->findBy(
                // On va chercher les saisons du film fourni
                ['movie' => $movie],
                ['number' => 'ASC']
            ),
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/new/movie/{id}", name="back_season_new", methods={"GET", "POST"})
     */
    public function new(Request $request, Movie $movie, SeasonRepository $seasonRepository): Response
    {
        $season = new Season();

        // On associe le film à la saison
        $season->setMovie($movie);

        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seasonRepository->add($season);
            $this->addFlash('success', 'Saison ajoutée.');
            return $this->redirectToRoute('back_season_index', ['id' => $movie->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/season/new.html.twig', [
            'season' => $season,
            'form' => $form,
            'movie' => $movie,
        ]);
    }

    /**
     * @Route("/{id}", name="back_season_show", methods={"GET"})
     */
    public function show(Season $season): Response
    {
        return $this->render('back/season/show.html.twig', [
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="back_season_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Season $season, SeasonRepository $seasonRepository): Response
    {
        $form = $this->createForm(SeasonType::class, $season);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $seasonRepository->add($season);
            $this->addFlash('success', 'Saison modifiée.');
            return $this->redirectToRoute('back_season_index', ['id' => $season->getMovie()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/season/edit.html.twig', [
            'season' => $season,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="back_season_delete", methods={"POST"})
     */
    public function delete(Request $request, Season $season, SeasonRepository $seasonRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$season->getId(), $request->request->get('_token'))) {
            $seasonRepository->remove($season);
            $this->addFlash('success', 'Saison supprimée.');
        }

        return $this->redirectToRoute('back_season_index', ['id' => $season->getMovie()->getId()], Response::HTTP_SEE_OTHER);
    }
}
