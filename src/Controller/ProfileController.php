<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil')]
class ProfileController extends AbstractController
{
    // list all users for dev
    #[Route('/list', name: 'app_profile_list', methods: ['GET'])]
    public function list(UserRepository $userRepository): Response
    {
        return $this->render('profile/list.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * Personal profile of connected user
     *
     * @param UserRepository $userRepository
     * @return Response
     */
    #[Route('/', name: 'app_profile_index', methods: ['GET'])]
    public function index(): Response
    {
        /** @var User */
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user
        ]);
    }

    /**
     * Edit profile for connected user
     */
    #[Route('/editer', name: 'app_profile_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var User */
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * Public profile of a user
     */
    #[Route('/{slug}', name: 'app_profile_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        return $this->render('profile/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * Delete profile for connected user
     */
    #[Route('/{id}', name: 'app_profile_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
    }
}
