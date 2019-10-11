<?php

namespace App\Controller\Teacher;

use App\Entity\Invitation;
use App\Form\SendInvitationType;
use App\Repository\InvitationRepository;
use App\Repository\MentorRepository;
use App\Repository\ReportRepository;
use App\Repository\ResultRepository;
use App\Repository\UserRepository;
use App\Service\Random;
use Doctrine\Common\Persistence\ObjectManager;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swift_Mailer;

/**
 * @Route("/teacher")
 */
class TeacherController extends AbstractController
{
    /**
     * @Route("/", name="app_teacher")
     *
     * @param Request               $request
     * @param UserRepository        $userRepository
     * @param ObjectManager         $manager
     * @param Random                $random
     * @param Swift_Mailer          $mailer
     * @param InvitationRepository  $invitationRepository
     * @param MentorRepository      $mentorRepository
     * @param ReportRepository      $reportRepository
     * @param ResultRepository      $resultRepository
     *
     * @return RedirectResponse|Response
     */
    public function index(Request $request, UserRepository $userRepository, ObjectManager $manager, Random $random, Swift_Mailer $mailer, InvitationRepository $invitationRepository, MentorRepository $mentorRepository, ReportRepository $reportRepository, ResultRepository $resultRepository): Response
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

        return $this->render('teacher/index.html.twig', [
            'formInvitation' => $formInvitation->createView(),
            'listStudents' => $listStudents
        ]);
    }

    /**
     * @Route("/listReports", name="teacher_list_reports", methods="GET")
     *
     * @param ReportRepository      $reportRepository
     * @param Request               $request
     * @param PaginatorInterface    $paginator
     *
     * @return Response
     */
    public function listReports(ReportRepository $reportRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $reportRepository->findBy(array(), ['dateAt' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('teacher/listReports.html.twig', [
            'reports' => $pagination,
            'teacher' => true
        ]);
    }

    /**
     * @Route("/listReports/{id}", name="teacher_list_reports_user", methods="GET")
     *
     * @param Int                   $id
     * @param Request               $request
     * @param PaginatorInterface    $paginator
     * @param ReportRepository      $reportRepository
     *
     * @return Response
     */
    public function listRepostsByUser(Int $id, Request $request, PaginatorInterface $paginator, ReportRepository $reportRepository): Response
    {
        $pagination = $paginator->paginate(
            $reportRepository->findByUser($id),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('teacher/listReports.html.twig', [
            'reports' => $pagination,
            'teacher' => true
        ]);
    }

    /**
     * @Route("/listResults", name="teacher_list_results", methods="GET")
     *
     * @param PaginatorInterface    $paginator
     * @param Request               $request
     * @param ResultRepository      $resultRepository
     *
     * @return Response
     */
    public function listResults(PaginatorInterface $paginator, Request $request, ResultRepository $resultRepository): Response
    {
        $pagination = $paginator->paginate(
            $resultRepository->findBy(array(), ['dateAt' => 'DESC']),
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('teacher/listResults.html.twig', [
            'results' => $pagination,
            'teacher' => true
        ]);
    }

    /**
     * @Route("/listResults/{id}", name="teacher_list_results_user", methods="GET")
     *
     * @param Int                   $id
     * @param Request               $request
     * @param PaginatorInterface    $paginator
     * @param ResultRepository      $resultRepository
     *
     * @return Response
     */
    public function listResultsByUser(Int $id, Request $request, PaginatorInterface $paginator, ResultRepository $resultRepository): Response
    {
        $pagination = $paginator->paginate(
            $resultRepository->findByUser($id),
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('teacher/listResults.html.twig', [
            'results' => $pagination,
            'teacher' => true
        ]);
    }
}


// FIXME: Créer des questionnaires
