<?php

namespace App\Controller;

use App\Entity\Signal;
use App\Form\SignalType;
use App\Repository\TripRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SignalmentController extends AbstractController
{
    #[Route('/signalment/{type}/{id}', name: 'app_signalment_index')]
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


        $signal = new Signal;
        $signal->setMember($this->getUser())
            ->setType($type)
            ->setNumber($id)
            ->setReason('NOT_CORRECT');

        $form = $this->createForm(SignalType::class, $signal);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($signal);
            $em->flush();

            $this->addFlash('success', 'Signalement envoyé');

            return $this->redirectToRoute('app_planning_index');
        }

        return $this->render('signalment/index.html.twig', [
            'entity' => $entity,
            'form' => $form,
        ]);
    }
}
