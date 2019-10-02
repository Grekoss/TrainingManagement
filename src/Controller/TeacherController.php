<?php

namespace App\Controller;

use App\Entity\Invitation;
use App\Form\SendInvitationType;
use App\Repository\InvitationRepository;
use App\Repository\MentorRepository;
use App\Repository\ReportRepository;
use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use App\Service\Random;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Swift_Mailer;

class TeacherController extends AbstractController
{
    /**
     * @Route("/teacher", name="app_teacher")
     */
    public function index(Request $request, UserRepository $userRepository, ObjectManager $manager, Random $random, Swift_Mailer $mailer, InvitationRepository $invitationRepository, MentorRepository $mentorRepository, ReportRepository $reportRepository, ResultRepository $resultRepository)
    {
        $formInvitation = $this->createForm(SendInvitationType::class);
        $formInvitation->handleRequest($request);

        if ($formInvitation->isSubmitted() && $formInvitation->isValid()) {

            $inviteMail = $formInvitation->get('email')->getData();
            $token = $random->randomPassword('all', 100);

            // Controle si cette adresse existe déjà
            $user = $userRepository->findOneBy(['email' => $inviteMail]);
            if ($user) {
                $this->addFlash(
                    'danger',
                    'Un utilisateur utilise déjà cette adresse mail !'
                );

                return $this->redirectToRoute('app_teacher');
            }

            // Controle si l'inviation a déjà été faite
            $invited = $invitationRepository->findOneBy(['mail' => $inviteMail]);
            if ($invited) {
                $this->addFlash(
                    'danger',
                    'Une invitation a déjà été envoyé à cette adresse mail !'
                );

                return $this->redirectToRoute('app_teacher');
            }

            $invitation = new Invitation();
            $invitation->setMail($inviteMail)
                ->setToken($token);

            $manager->persist($invitation);
            $manager->flush();

            // Envoie du message:
            $message = new \Swift_Message();
            // FIXME: Modifier le titre
            $message->setSubject('Inscription à l\'application')
                ->setFrom(['send@example.com' => 'Exemple'])
                ->setTo($inviteMail)
                ->setBody(
                    $this->renderView('emails/inviteUser.html.twig', [
                        'data' => $formInvitation->getData(),
                        'token' => $token
                        ]
                    ),
                    'text/html'
                );
            $mailer->send($message);
        }

        $listStudents = [];
        $listTmpStudents = $mentorRepository->findBy(['mentor' => $this->getUser()]);
        for ( $i=0 ; $i<count($listTmpStudents) ; $i++ ) {
            $listStudents[$i]['user'] = $listTmpStudents[$i]->getStudent();
            $listStudents[$i]['reports'] = $reportRepository->findByUser($listStudents[$i]['user']);
            $listStudents[$i]['results'] = $resultRepository->findByUser($listStudents[$i]['user']);
        }

        dump($listStudents);

        return $this->render('teacher/index.html.twig', [
            'formInvitation' => $formInvitation->createView(),
            'listStudents' => $listStudents
        ]);
    }
}

// FIXME: Afficher la liste des étudiants qu'il a en suppervision
// FIXME: Créer des questionnaires
// FIXME: Ecrire à ses étudiants
// FIXME: Ajouter des cours
// FIXME: Afficher la listes des résultatsquestionaires
// FIXME: Afficher tout les rapport des étudiants avec un affichage particulier pour ses filleule
