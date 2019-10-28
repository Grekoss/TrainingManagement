<?php

namespace App\Controller\Teacher;

use App\Entity\Mentor;
use App\Entity\User;
use App\Form\MentorType;
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

            // Ajouter seulement que les étudiants actifs
            if ($listUsersTMP[$i]->getRole() === 'Etudiant' && $listUsersTMP[$i]->getIsActive(true)) {
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
     * @Route("/teacher/listUsers", name="teacher_list_users")
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

    /**
     * @Route("/teacher/user/{id}/isActive", name="teacher_user_isActive")
     *
     * @param User  $user
     *
     * @return Response
     */
    public function userIsActive(User $user): Response
    {
        if ($user->getIsActive(true)) {
            $user->setIsActive(false);

            $this->manager->persist($user);
            $this->manager->flush();
        } else {
            $user->setIsActive(true);

            $this->manager->persist($user);
            $this->manager->flush();
        }

        return $this->redirectToRoute('teacher_list_users');
    }

    /**
     * @Route("/teacher/user/{id}/update", name="teacher_user_update")
     */
    public function userUpdate(User $user, Request $request)
    {
        // On vérifie si mentor sinon on en créer un!
        $mentor = $this->mentorRepository->findOneBy(['student' => $user->getId()]);

        if (!$mentor) {
            $mentor = new Mentor();
        }

        $form = $this->createForm(MentorType::class, $mentor);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->manager->persist($mentor);
            $this->manager->flush();

            $this->addFlash('success', 'Modification du mentor validé !');

            return $this->redirectToRoute('teacher_list_users');
        }

        return $this->render('teacher/user/show.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }
}
