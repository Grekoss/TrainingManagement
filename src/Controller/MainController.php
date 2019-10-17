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
        // Enregistrer la connection
        $user = $this->getUser();
        $user->setLastLogin(new \DateTime());
        $this->getDoctrine()->getManager()->persist($user);
        $this->getDoctrine()->getManager()->flush();

        return $this->render('main/dashboard.html.twig', [
        ]);
    }
}
