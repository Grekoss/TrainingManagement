<?php

namespace App\Controller;

use App\Entity\Report;
use App\Repository\ReportRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function newReport()
    {
        return $this->render('report/add.html.twig', [

        ]);
    }

    /**
     * @Route("/report/{id}/show", name="app_report_show")
     */
    public function showReport(Report $report)
    {
        return $this->render('report/show.html.twig', [
            'report' => $report
        ]);
    }
}
