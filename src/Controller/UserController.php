<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user", name="app_user_profile")
     */
    public function profile()
    {
        return $this->render('user/profile.html.twig', [
        ]);
    }
}
