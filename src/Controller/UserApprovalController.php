<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Speciality;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserApprovalController extends AbstractController
{
    #[Route('/user-approval', name: 'user_approval_list')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les utilisateurs avec le rôle ROLE_WAIT
        $users = $entityManager->getRepository(User::class)->findByRole('ROLE_WAIT');

        return $this->render('user_approval/index.html.twig', [
            'users' => $users,
        
        ]);
    }

    #[Route('/user-approve/{id}', name: 'user_approve')]
    public function approve(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Récupérer l'utilisateur par son ID
        $user = $entityManager->getRepository(User::class)->find($id);

        if ($user) {
            // Modifier le rôle de l'utilisateur en ROLE_ADMIN
            $user->setRoles(['ROLE_DOCTOR']);
            $doctor = new Doctor();
           
            // Remplir les données du Doctor à partir de l'utilisateur
            $doctor->setEmail($user->getEmail());
            $doctor->setFirstName($user->getFirstName());
            $doctor->setLastName($user->getLastName());
            $doctor->setPhone($user->getPhone());
            $doctor->setAdress($user->getAdress());
         
    
            // Si des données spécifiques au Doctor sont nécessaires
            $speciality = $entityManager->getRepository(Speciality::class)->findOneBy([]); // Exemple : récupérer une spécialité par défaut
            if ($speciality) {
                $doctor->setSpeciality($speciality);
            }
    
            // Persister l'entité Doctor
            $entityManager->persist($doctor);
           
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_approval_list');
    }

    #[Route('/user-reject/{id}', name: 'user_reject')]
    public function reject(int $id, EntityManagerInterface $entityManager): RedirectResponse
    {
        // Récupérer l'utilisateur par son ID
        $user = $entityManager->getRepository(User::class)->find($id);

        if ($user) {
            // Supprimer l'utilisateur
            $entityManager->remove($user);
            $entityManager->flush();
        }

        return $this->redirectToRoute('user_approval_list');
    }
}
