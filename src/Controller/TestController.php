<?php

namespace App\Controller;

use App\Enum\ShiftEnum;
use App\Repository\MentorRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class TestController extends AbstractController
{
    /**
     * @Route("/test", name="test")
     */
    public function index(MentorRepository $mentorRepository)
    {
        dump($mentorRepository->findOneBy(
            ['student' => $this->getUser()]
        ));

        dump($this->getUser());

        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
