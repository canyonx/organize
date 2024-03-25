<?php

namespace App\Controller;

use App\Entity\User;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * Register a new user
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordHasher
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger
    ): Response {

        if ($this->getUser()) {
            return $this->redirectToRoute('app_profile_index');
        }

        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $user->setCreatedAt(new \DateTimeImmutable())
                ->setLastConnAt(new \DateTimeImmutable())
                ->setSlug(strtolower($slugger->slug($user->getPseudo(), '_')));

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation(
                'app_verify_email',
                $user,
                (new TemplatedEmail())
                    ->from(new Address($this->getParameter('app_admin_mail'), 'OrganiZe'))
                    ->to($user->getEmail())
                    ->subject('Confirme ton email - Organize')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            $this->addFlash('success', 'Compte crée, confirme ton email avant de te connecter !');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    /**
     * Verify email for a new user
     *
     * @param Request $request
     * @param TranslatorInterface $translator
     * @return Response
     */
    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        TranslatorInterface $translator,
        UserRepository $userRepository
    ): Response {

        // $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $id = $request->query->get('id'); // retrieve the user id from the url

        // Verify the user id exists and is not null
        if (null === $id) {
            return $this->redirectToRoute('app_home');
        }

        $user = $userRepository->find($id);

        // Ensure the user exists in persistence
        if (null === $user) {
            return $this->redirectToRoute('app_home');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('danger', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Email vérifié ! Tu peux te connecter.');

        return $this->redirectToRoute('app_login');
    }
}
