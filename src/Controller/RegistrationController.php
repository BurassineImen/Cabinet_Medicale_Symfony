<?php

namespace App\Controller;

use App\Entity\Speciality;
use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\SpecialityRepository;

class RegistrationController extends AbstractController
{
    #[Route('/register/{role}', name: 'app_register')]
    public function register(
        string $role,
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        EntityManagerInterface $entityManager,
        SpecialityRepository $specialityRepository
    ): Response {
        $user = new User();
    

        $specialities = $specialityRepository->findAll();

        // Passer les options au formulaire
        $form = $this->createForm(RegistrationFormType::class, $user, [
            'role' => $role,
            'specialities' => $specialities,
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($role === 'medecin') {
                $speciality = $form->get('speciality')->getData();
                if ($speciality) {
                    $user->setSpeciality($speciality);
                }
                $user->setRoles(['ROLE_WAIT']);

            }
            if ($role === 'patient') {
                $user->setSpeciality(null);
                $user->setRoles(['ROLE_PATIENT']);
            }

            $user->setPassword($userPasswordHasher->hashPassword($user, $user->getPassword()));

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'role' => $role,
        ]);
    }
}
