<?php

namespace App\Controller;

use App\Entity\Review;
use App\Entity\Movie;
use App\Repository\MovieRepository;
use App\Entity\Comment;
use App\Form\ReviewType;
use App\Repository\ReviewRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ReviewController extends AbstractController
{
        /**
     * Add (display and process form)
     * 
     * @Route("/review/add/{id}", name="review_add", methods={"GET", "POST"})
     */
    public function add(Request $request, ManagerRegistry $doctrine, Movie $movie): Response
    {
        // On crée une entité "Doctrine" (on va la "mapper" sur le form)
        $review = new Review();

        // Création du formulaire d'ajout d'un article
        // @link https://symfony.com/doc/5.4/forms.html#creating-forms-in-controllers
        $form = $this->createForm(ReviewType::class, $review);
        
        // On demande au formulaire d'inspecter la requête et de la traiter
        $form->handleRequest($request);

        // Si form posté, et qu'il est valide
        if ($form->isSubmitted() && $form->isValid()) {

            $review->setMovie($movie);
            // Le formulaire a mis à jour l'entité $post automatiquement

            // On va faire appel au Manager de Doctrine
            $entityManager = $doctrine->getManager();
            // Prépare-toi à "persister" notre objet (req. INSERT INTO)
            $entityManager->persist($review);

            // On exécute les requêtes SQL
            $entityManager->flush();

            // On redirige vers la liste
            return $this->redirectToRoute('home');
        }

        // On affiche le form
        // @link https://symfony.com/doc/5.4/forms.html#rendering-forms
        return $this->renderForm('review/add.html.twig', [
            'form' => $form,
            'currentMovie' => $movie,   
        ]);
    }

    
}