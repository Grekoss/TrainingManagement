<?php

namespace App\Controller\Teacher;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends TeacherController
{
    private function createListUsers()
    {
        // Récupération de tout les users
        $listUsersTMP = $this->userRepository->findAll();

        $listUsers = array();

        // Création du tableau associatif en fonction de l'ID de l'user
        for ( $i=0 ; $i<count($listUsersTMP) ; $i++ ) {

            // Ajouter seulement que les étudiants
            if ($listUsersTMP[$i]->getRole() === 'Etudiant') {
                $key = $listUsersTMP[$i]->getId();

                $mentor = $this->mentorRepository->findOneBy(['student' => $key]);
                if ($mentor) {
                    $mentor = $mentor->getMentor();
                }

                $listUsers[$key] = [
                    'user' => $listUsersTMP[$i],
                    'mentor' => $mentor
                ];
            }
        }

        return $listUsers;
    }

    /**
     * @Route("/listUsers", name="teacher_list_users")
     *
     * @param Request   $request
     *
     * @return Response
     */
    public function listUsers(Request $request): Response
    {
        $listUsers = $this->createListUsers();

        $pagination = $this->paginator->paginate(
            $listUsers,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('teacher/listUsers.html.twig', [
            'users' => $pagination,
        ]);
    }
}
