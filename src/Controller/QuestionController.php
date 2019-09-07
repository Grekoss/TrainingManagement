<?php

namespace App\Controller;

use App\Entity\Quizzes;
use App\Repository\QuizzesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question", name="app_question")
     */
    public function index(QuizzesRepository $quizzesRepository)
    {
        $quizzes = $quizzesRepository->findBy(array(), array('updatedAt' => 'DESC'));

        return $this->render('question/index.html.twig', [
            'quizzes' => $quizzes,
        ]);
    }

    /**
     * @Route("/question/{id}/show", name="app_question_show")
     */
    public function questions(Quizzes $quizzes)
    {
        return $this->render('question/show.html.twig', [

        ]);
    }
}
