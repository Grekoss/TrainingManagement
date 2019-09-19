<?php

namespace App\Controller;

use App\Enum\ShiftEnum;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(UserRepository $userRepository)
    {
        $test = $userRepository->userForManageRush();

        dump($test);

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
