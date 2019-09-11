<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReportController extends AbstractController
{
    /**
     * @Route("/report", name="app_report")
     */
    public function index()
    {
        return $this->render('report/index.html.twig', [
            'controller_name' => 'ReportController',
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

//    FIXME: Remettre l'id à la place du chiffre mis temporairement pour les essais
    /**
     * @Route("/report/123/show", name="app_report_show")
     */
    public function showReport()
    {
        return $this->render('report/show.html.twig', [

        ]);
    }
}
