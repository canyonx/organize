<?php

namespace App\Controller;

use App\Entity\Signalment;
use App\Form\SignalmentType;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use App\Service\NotificationService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SignalmentController extends AbstractController
{
    #[Route('/signalement/{type}/{id}', name: 'app_signalment_index')]
    public function index(
        string $type,
        int $id,
        TripRepository $tripRepository,
        UserRepository $userRepository,
        Request $request,
        EntityManagerInterface $em
    ): Response {

        if ($type != ('user' || 'trip') && !$id) throw $this->createNotFoundException('Signalement invalide');

        if ($type == 'trip') $entity = $tripRepository->find($id);
        if ($type == 'user') $entity = $userRepository->find($id);

        if (!$entity) throw $this->createNotFoundException('Signalement invalide');

        $signal = new Signalment;
        $signal->setMember($this->getUser())
            ->setType($type)
            ->setNumber($id)
            ->setReason('NOT_CORRECT');

        $form = $this->createForm(SignalmentType::class, $signal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($signal);
            $em->flush();

            // TODO : send email to admin 

            $this->addFlash('success', '<i class="fa-solid fa-circle-check fa-xl"></i> Signalement envoyé');

            return $this->redirectToRoute('app_planning_index');
        }

        return $this->render('signalment/index.html.twig', [
            'entity' => $entity,
            'form' => $form,
        ]);
    }
}
