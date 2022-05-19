<?php

namespace App\Controller\Front;

use App\Entity\Movie;
use App\Entity\Review;
use App\Form\ReviewType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ReviewController extends AbstractController
{
    /**
     * @Route("/movie/{slug}/review/add", name="review_add")
     */
    public function add(Movie $movie = null, Request $request, ManagerRegistry $doctrine): Response
    {
        // 404 ?
        if ($movie === null) {
            throw $this->createNotFoundException('Film non trouvé.');
        }

        // Entité Review
        $review = new Review();

        // Le form, et l'entité mappée dessus
        $form = $this->createForm(ReviewType::class, $review);

        // Prise en charge de la requête par le form
        $form->handleRequest($request);

        // Form soumis et valide ?
        if ($form->isSubmitted() && $form->isValid()) {

            // On associe le film à la review
            $review->setMovie($movie);

            $entityManager = $doctrine->getManager();
            $entityManager->persist($review);
            $entityManager->flush();

            return $this->redirectToRoute('movie_show', ['id' => $movie->getId()]);
        }

        // /!\ renderForm() si on trnasmet un formulaire
        return $this->renderForm('front/review/add.html.twig', [
            'movie' => $movie,
            'form' => $form,
        ]);
    }
}
