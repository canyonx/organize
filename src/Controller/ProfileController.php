<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Entity\Setting;
use App\Form\SettingType;
use App\Form\ChangePasswordType;
use App\Repository\UserRepository;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

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
     */
    #[Route('/', name: 'app_profile_index', methods: ['GET', 'POST'])]
    public function index(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        /** @var User */
        $user = $this->getUser();

        $setting = $user->getSetting() ?: new Setting();
        $form = $this->createForm(SettingType::class, $setting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $setting->setMember($user);
            $em->persist($setting);
            $em->flush();

            return $this->redirectToRoute('app_profile_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'formSetting' => $form
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
     * User Edit Password
     */
    #[Route(path: '/edit-password', name: 'app_profile_edit_password')]
    public function editPassword(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ) {
        $this->denyAccessUnlessGranted('ROLE_USER');

        /** @var User */
        $user = $this->getUser();
        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $form["newPassword"]->getData();
            $hash = $hasher->hashPassword($user, $password);
            $user->setPassword($hash);
            $em->persist($user);
            $em->flush();

            $this->addFlash('success', 'Le mot de passe à été changé');
            return $this->redirectToRoute('app_profile_index');
        }

        return $this->render('profile/change_password.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * Public profile of a user
     */
    #[Route('/{slug}', name: 'app_profile_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('USER_VIEW', $user);

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
