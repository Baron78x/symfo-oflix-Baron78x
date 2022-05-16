<?php

namespace App\Controller\Back;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BackController extends AbstractController
{
    /**
     * @Route("/back", name="back_back_menu")
     */
    public function index(): Response
    {
        return $this->render('back/menu.html.twig', [
            'controller_name' => 'BackController',
        ]);
    }
}
