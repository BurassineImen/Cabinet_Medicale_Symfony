<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AppointmentController extends AbstractController
{
    #[Route('/appointment', name: 'app_appointment')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupère tous les rendez-vous
        $appointments = $entityManager->getRepository(Appointment::class)->findAll();

        return $this->render('appointment/index.html.twig', [
            'appointments' => $appointments,
        ]);
    }

    #[Route('/appointment/new', name: 'appointment_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $appointment = new Appointment();
        $form = $this->createForm(AppointmentType::class, $appointment);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($appointment);
            $entityManager->flush();

            $this->addFlash('success', 'Le rendez-vous a été enregistré avec succès.');

            return $this->redirectToRoute('app_appointment');
        }

        return $this->render('appointment/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/appointment/{id}', name: 'appointment_show', requirements: ['id' => '\d+'])]
    public function show(int $id, EntityManagerInterface $entityManager): Response
    {
        $appointment = $entityManager->getRepository(Appointment::class)->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('Le rendez-vous demandé n\'existe pas.');
        }

        return $this->render('appointment/show.html.twig', [
            'appointment' => $appointment,
        ]);
    }

    #[Route('/appointment/{id}/edit', name: 'appointment_edit', requirements: ['id' => '\d+'])]
    public function edit(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $appointment = $entityManager->getRepository(Appointment::class)->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('Le rendez-vous demandé n\'existe pas.');
        }

        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Le rendez-vous a été modifié avec succès.');

            return $this->redirectToRoute('app_appointment');
        }

        return $this->render('appointment/edit.html.twig', [
            'form' => $form->createView(),
            'appointment' => $appointment,
        ]);
    }

    #[Route('/appointment/{id}/delete', name: 'appointment_delete', methods: ['POST'])]
    public function delete(Request $request, int $id, EntityManagerInterface $entityManager): Response
    {
        $appointment = $entityManager->getRepository(Appointment::class)->find($id);

        if (!$appointment) {
            throw $this->createNotFoundException('Le rendez-vous demandé n\'existe pas.');
        }

        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($appointment);
            $entityManager->flush();

            $this->addFlash('success', 'Le rendez-vous a été supprimé avec succès.');
        }

        return $this->redirectToRoute('app_appointment');
    }
}
