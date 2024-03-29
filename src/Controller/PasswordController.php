<?php

namespace App\Controller;

use App\Form\NewPasswordType;
use App\Form\LostPasswordType;
use App\Repository\UserRepository;
use Symfony\Component\Mime\Address;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\NotificationEmail;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class PasswordController extends AbstractController
{

    /**
     * Lost Password
     * Entrer you email to recive a personal url to set a new password
     */
    #[Route(path: '/password/lost', name: 'app_password_lost')]
    public function lostPassword(
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        TokenGeneratorInterface $tokenGenerator,
        MailerInterface $mailer
    ) {
        $form = $this->createForm(LostPasswordType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $user = $userRepository->findOneBy(['email' => $form["email"]->getData()]);

            if (!$user) {
                $this->addFlash('success', 'Merci de bien vouloir vous enregistrer avant d\'oublier votre mot de passe');
                return $this->redirectToRoute('app_register');
            }

            //génère un Url avec token aléatoire
            $token = $tokenGenerator->generateToken();
            $user->setResetToken($token);
            $em->flush();
            $url = $this->generateUrl('app_password_new', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

            // Envoi un mail avec le new mdp
            $email = (new NotificationEmail())
                ->from(new Address($this->getParameter('app_admin_mail'), $this->getParameter('app_site_name')))
                ->to($user->getEmail())
                ->importance('')
                ->subject('Mot de passe perdu')
                ->htmlTemplate("email/lost_password.html.twig")
                ->context([
                    'message' => 'Clique ici pour choisir un nouveau mot de passe',
                    'url' => $url
                ]);

            $mailer->send($email);

            $this->addFlash('success', 'Un lien de changement de mot de passe à été envoyé a votre adresse');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('password/lost_password.html.twig', [
            'form' => $form
        ]);
    }

    /**
     * New Password
     * Personal url to choose a new password
     */
    #[Route(path: '/password/{token}', name: 'app_password_new')]
    public function newPassword(
        string $token,
        Request $request,
        UserRepository $userRepository,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ) {
        $user = $userRepository->findOneBy(['resetToken' => $token]);

        if ($user) {
            $form = $this->createForm(NewPasswordType::class);
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {

                $user->setResetToken('');
                $password = $hasher->hashpassword($user, $form->get('newPassword')->getData());
                $user->setPassword($password);
                $em->flush();

                $this->addFlash('success', 'Mot de passe changé avec succès');
                return $this->redirectToRoute('app_login');
            }

            return $this->render('password/new_password.html.twig', [
                'form' => $form
            ]);
        }

        $this->addFlash('info', 'Ce lien a expiré');
        return $this->redirectToRoute('app_login');
    }
}
