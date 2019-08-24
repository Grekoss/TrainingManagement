<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    /**
     * @Route("/dashboard", name="app_dashboard")
     */
    public function index()
    {
        return $this->render('main/dashboard.html.twig', [
        ]);
    }
}
