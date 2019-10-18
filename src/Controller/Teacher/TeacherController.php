<?php

namespace App\Controller\Teacher;

use App\Entity\Invitation;
use App\Form\SendInvitationType;
use App\Repository\InvitationRepository;
use App\Repository\MentorRepository;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
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

class TeacherController extends AbstractController
{
    protected $mentorRepository;
    protected $userRepository;
    protected $invitationRepository;
    protected $reportRepository;
    protected $resultRepository;
    protected $questionRepository;
    protected $quizRepository;

    protected $manager;
    protected $paginator;

    public function __construct(MentorRepository $mentorRepository, UserRepository $userRepository, ObjectManager $manager, InvitationRepository $invitationRepository, ReportRepository $reportRepository, ResultRepository $resultRepository, PaginatorInterface $paginator, QuestionRepository $questionRepository, QuizRepository $quizRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->quizRepository = $quizRepository;
        $this->mentorRepository = $mentorRepository;
        $this->userRepository = $userRepository;
        $this->invitationRepository = $invitationRepository;
        $this->reportRepository = $reportRepository;
        $this->resultRepository = $resultRepository;
        $this->manager = $manager;
        $this->paginator = $paginator;
    }

    /**
     * @Route("/teacher/", name="app_teacher")
     *
     * @param Request               $request
     * @param Random                $random
     * @param Swift_Mailer          $mailer
     *
     * @return RedirectResponse|Response
     */
    public function index(Request $request, Random $random, Swift_Mailer $mailer): Response
    {
        $formInvitation = $this->createForm(SendInvitationType::class);
        $formInvitation->handleRequest($request);

        if ($formInvitation->isSubmitted() && $formInvitation->isValid()) {

            $inviteMail = $formInvitation->get('email')->getData();
            $token = $random->randomPassword('all', 100);

            // Controle si cette adresse existe déjà
            $user = $this->userRepository->findOneBy(['email' => $inviteMail]);
            if ($user) {
                $this->addFlash(
                    'danger',
                    'Un utilisateur utilise déjà cette adresse mail !'
                );

                return $this->redirectToRoute('app_teacher');
            }

            // Controle si l'inviation a déjà été faite
            $invited = $this->invitationRepository->findOneBy(['mail' => $inviteMail]);
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

            $this->manager->persist($invitation);
            $this->manager->flush();

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

            $this->addFlash('success', 'Invitation envoyée !');
        }

        $listStudents = [];
        $listTmpStudents = $this->mentorRepository->findBy(['mentor' => $this->getUser()]);
        for ( $i=0 ; $i<count($listTmpStudents) ; $i++ ) {
            $listStudents[$i]['user'] = $listTmpStudents[$i]->getStudent();
            $listStudents[$i]['reports'] = $this->reportRepository->findByUser($listStudents[$i]['user']);
            $listStudents[$i]['results'] = $this->resultRepository->findByUser($listStudents[$i]['user']);
        }

        return $this->render('teacher/index.html.twig', [
            'formInvitation' => $formInvitation->createView(),
            'listStudents' => $listStudents
        ]);
    }

    /**
     * @Route("/teacher/listReports", name="teacher_list_reports", methods="GET")
     *
     * @param Request               $request
     *
     * @return Response
     */
    public function listReports(Request $request): Response
    {
        $pagination = $this->paginator->paginate(
            $this->reportRepository->findBy(array(), ['dateAt' => 'DESC']),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('teacher/listReports.html.twig', [
            'reports' => $pagination,
            'teacher' => true
        ]);
    }

    /**
     * @Route("/teacher/listReports/{id}", name="teacher_list_reports_user", methods="GET")
     *
     * @param Int                   $id
     * @param Request               $request
     *
     * @return Response
     */
    public function listReportsByUser(Int $id, Request $request): Response
    {
        $pagination = $this->paginator->paginate(
            $this->reportRepository->findByUser($id),
            $request->query->getInt('page', 1),
            6
        );

        return $this->render('teacher/listReports.html.twig', [
            'reports' => $pagination,
            'teacher' => true
        ]);
    }

    /**
     * @Route("/teacher/listResults", name="teacher_list_results", methods="GET")
     *
     * @param Request               $request
     *
     * @return Response
     */
    public function listResults(Request $request): Response
    {
        $pagination = $this->paginator->paginate(
            $this->resultRepository->findBy(array(), ['dateAt' => 'DESC']),
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('teacher/listResults.html.twig', [
            'results' => $pagination,
            'teacher' => true
        ]);
    }

    /**
     * @Route("/teacher/listResults/{id}", name="teacher_list_results_user", methods="GET")
     *
     * @param Int                   $id
     * @param Request               $request
     *
     * @return Response
     */
    public function listResultsByUser(Int $id, Request $request): Response
    {
        $pagination = $this->paginator->paginate(
            $this->resultRepository->findByUser($id),
            $request->query->getInt('page', 1),
            7
        );

        return $this->render('teacher/listResults.html.twig', [
            'results' => $pagination,
            'teacher' => true
        ]);
    }
}
