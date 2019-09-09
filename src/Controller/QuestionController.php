<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Repository\QuizRepository;
use App\Repository\ResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/question", name="app_question")
     */
    public function index(QuizRepository $quizRepository, ResultRepository $resultRepository)
    {
        $quizzes = $quizRepository->findBy(array(), array('updatedAt' => 'DESC'));

        $results = array();
        for ( $i=0 ; $i<count($quizzes) ; $i++ ) {
            $results[$i] = $resultRepository->findByQuizAndUser($quizzes[$i], $this->getUser());
        }

        dump($results);

        return $this->render('question/index.html.twig', [
            'quizzes' => $quizzes,
            'results' => $results
        ]);
    }

    /**
     * @Route("/question/{id}/show", name="app_question_show")
     */
    public function questions(Quiz $quizzes)
    {
        return $this->render('question/show.html.twig', [

        ]);
    }
}
