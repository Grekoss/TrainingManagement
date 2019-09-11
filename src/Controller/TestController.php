<?php

namespace App\Controller;

use App\Enum\ShiftEnum;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index()
    {
        dump(rand(0,5));

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
