<?php

namespace App\Controller;

use App\Entity\Quiz;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\ResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/quizzes", name="app_question")
     */
    public function index(QuizRepository $quizRepository, ResultRepository $resultRepository)
    {
        $quizzes = $quizRepository->findBy(array(), array('updatedAt' => 'DESC'));

        $results = array();
        for ( $i=0 ; $i<count($quizzes) ; $i++ ) {
            $results[$i] = $resultRepository->findByQuizAndUser($quizzes[$i], $this->getUser());
        }

        return $this->render('question/index.html.twig', [
            'quizzes' => $quizzes,
            'results' => $results
        ]);
    }

    /**
     * @Route("/quizzes/{id}/show", name="app_question_show")
     */
    public function showQuestions(Quiz $quiz, QuestionRepository $questionRepository, ResultRepository $resultRepository)
    {
       // $questions = $questionRepository->findByQuiz($quiz);

        $results = $resultRepository->findByQuizAndUser($quiz, $this->getUser());

        return $this->render('question/show.html.twig', [
            'quiz' => $quiz,
            'questions' => $questions,
            'results' => $results

        ]);
    }
}
