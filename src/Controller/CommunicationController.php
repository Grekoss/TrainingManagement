<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CommunicationController extends AbstractController
{
    /**
     * @Route("/communication", name="app_communication")
     */
    public function index()
    {
        return $this->render('communication/index.html.twig', [
            'controller_name' => 'CommunicationController',
        ]);
    }
}
