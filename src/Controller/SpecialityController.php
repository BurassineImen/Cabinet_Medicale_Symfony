<?php

namespace App\Controller;

use App\Entity\Speciality;
use App\Form\SpecialityType;
use App\Repository\SpecialityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;




final class SpecialityController extends AbstractController{
    #[Route('/speciality', name: 'app_speciality')]
    public function index(SpecialityRepository $specialityRepository): Response
    {
        return $this->render('speciality/index.html.twig', [
            'specialities' => $specialityRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'speciality_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $speciality = new Speciality();
        $form = $this->createForm(SpecialityType::class, $speciality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($speciality);
            $entityManager->flush();

            return $this->redirectToRoute('app_speciality', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('speciality/new.html.twig', [
            'speciality' => $speciality,
            'form' => $form,
        ]);
    }

    #[Route('/speciality/{id}', name: 'speciality_show', methods: ['GET'])]
    public function show(Speciality $speciality): Response
    {
        return $this->render('speciality/show.html.twig', [
            'speciality' => $speciality,
        ]);
    }

    #[Route('/{id}/edit', name: 'speciality_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Speciality $speciality, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SpecialityType::class, $speciality);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_speciality', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('speciality/edit.html.twig', [
            'speciality' => $speciality,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'speciality_delete', methods: ['POST'])]
    public function delete(Request $request, Speciality $speciality, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$speciality->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($speciality);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_speciality', [], Response::HTTP_SEE_OTHER);
    }
}
