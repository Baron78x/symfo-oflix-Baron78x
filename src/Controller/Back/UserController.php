<?php

namespace App\Controller\Back;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Controller\Front\LoginController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * @Route("/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/user", name="back_user_index", methods={"GET"})
     */
    public function index(ManagerRegistry $doctrine): Response
    {
        $users = $doctrine->getRepository(User::class)->findAll();
        return $this->render('back/user/index.html.twig', [
            'users' => $users
        ]);
    }

    /**
     * @Route("/user/new", name="back_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $plaintextPassword = $request->request->get('user')['password'];           

            if(preg_match('/^(?=.*[0-9])(?=.*[A-ZÀ-ÖØ-Ÿ])(?=.*[a-zà-öø-ÿ])(?=.*[_|%&*=@$-])[0-9A-zÀ-ÖØ-öø-ÿ_|%&*=@$-]{8,}$/', $plaintextPassword)) {
                // hash the password (based on the security.yaml config for the $user class)
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);

                $entityManager = $doctrine->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
                $this->addFlash('success', 'User ajouté.');
                return $this->redirectToRoute('app_back_user_index', [], Response::HTTP_SEE_OTHER);
            } else {
                $this->addFlash('warning', 'Le mot de passe doit contenir minimum 8 caractères, une majuscule, une minuscule, un chiffre et un caractères spécial _|%&*=@$-');
            }
        }

        return $this->renderForm('back/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/user/{id}", name="back_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('back/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/user/{id}/edit", name="back_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, ManagerRegistry $doctrine, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $plaintextPassword = $request->request->get('user')['password'];           

            if (!empty($plaintextPassword)) {
                // hash the password (based on the security.yaml config for the $user class)
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plaintextPassword
                );
                $user->setPassword($hashedPassword);
            }

            $entityManager = $doctrine->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'User modifié.');
            return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/user/{id}", name="back_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, ManagerRegistry $doctrine): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager = $doctrine->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'User supprimé.');
        }

        return $this->redirectToRoute('back_user_index', [], Response::HTTP_SEE_OTHER);
    }
}