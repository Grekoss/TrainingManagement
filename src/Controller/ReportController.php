<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\CommentReport;
use App\Entity\Message;
use App\Entity\Report;
use App\Enum\RoleEnum;
use App\Form\CommentReportType;
use App\Form\ReportType;
use App\Form\SendMessageType;
use App\Repository\ReportRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="app_report")
     */
    public function index(ReportRepository $reportRepository)
    {
        $reports = $reportRepository->findByUser($this->getUser());

        return $this->render('report/index.html.twig', [
            'reports' => $reports
        ]);
    }

    /**
     * @Route("/report/new", name="app_report_new")
     */
    public function newReport(Request $request, ObjectManager $manager)
    {
        $report = new Report();

        $form = $this->createForm(ReportType::class, $report);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $report->setStudent($this->getUser());

            $manager->persist($report);
            $manager->flush();

            $this->addFlash(
                'success',
                'Rapport enregistré'
            );

            return $this->redirectToRoute('app_report');
        }

        return $this->render('report/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/report/{id}/show", name="app_report_show")
     */
    public function showReport(Report $report, Request $request, ObjectManager $manager)
    {
        // Verifier si la personne dispose du role Teacher
        $hasAccess = $this->isGranted('ROLE_TEACHER');
        if ($hasAccess) {
            $report->setIsSeen(true);

            $manager->persist($report);
            $manager->flush();
        }

        $comment = new CommentReport();
        $form = $this->createForm(CommentReportType::class, $comment);
        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $comment->setAuthor($this->getUser())
                ->setReport($report);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash(
                'success',
                'Le commentaire a bien été enregistré !'
            );

            return $this->redirectToRoute('app_report_show', [
                'id' => $report->getId()
            ]);
        }

        return $this->render('report/show.html.twig', [
            'report' => $report,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/report/{id}/update", name="app_report_update")
     */
    public function updateReport(ObjectManager $manager, Report $report, Request $request)
    {
        $form = $this->createForm(ReportType::class, $report);

        $form->handleRequest($request);
        if ( $form->isSubmitted() && $form->isValid() ) {
            $report->setStudent($this->getUser());

            $manager->persist($report);
            $manager->flush();

            $this->addFlash(
                'info',
                'Rapport modifié'
            );

            return $this->redirectToRoute('app_report');
        }

        return $this->render('report/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/report/comment/delete/{id}", name="app_comment_delete", methods="DELETE")
     */
    public function delete(CommentReport $commentReport, Request $request)
    {
        $report = $commentReport->getReport();

        if ($this->isCsrfTokenValid('delete'.$commentReport->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($commentReport);
            $em->flush();
        }

        $this->addFlash(
            'danger',
            'Votre commentaire a été supprimé !'
        );

        return $this->redirectToRoute('app_report_show', [
            'id' => $report->getId()
        ]);
    }

    /**
     * @Route("/report/listReport/{id}", name="report_list_report")
     */
    public function listReport(User $user, ReportRepository $reportRepository)
    {
        return $this->render('report/index.html.twig', [
           'teacher' => true,
            'reports' => $reportRepository->findByUser($user)
        ]);
    }
}
