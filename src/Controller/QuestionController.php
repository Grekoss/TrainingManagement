<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
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
    public function showQuestions(Quiz $quiz, ResultRepository $resultRepository, QuestionRepository $questionRepository)
    {
        $results = $resultRepository->findByQuizAndUser($quiz, $this->getUser());

        // Afficher une question du question en aléatoire
        $questions = $questionRepository->findByQuiz($quiz);
        shuffle($questions);

        return $this->render('question/show.html.twig', [
            'quiz' => $quiz,
            'results' => $results,
            'sampleQuestion' => $questions[0]

        ]);
    }

    /**
     * @Route("/quizzes/correction/{id}", name="app_correction")
     */
    public function showCorrection(Result $result)
    {
        return $this->render('question/correction.html.twig', [
            'result' => $result
        ]);
    }

    /**
     * @Route("quizzes/start-quiz/{id}", name="app_start_quiz")
     */
    public function startQuiz(Quiz $quiz, QuestionRepository $questionRepository)
    {
        $questions = $questionRepository->findByQuiz($quiz);

        //On mélange les réponses de chaque questions
        $mixedAnswers = array();
        for ( $i=0 ; $i<count($questions) ; $i++ ) {
            $listAnswers = [$questions[$i]->getProp1(), $questions[$i]->getProp2(), $questions[$i]->getProp3(), $questions[$i]->getProp4()];
            shuffle($listAnswers);
            $mixedAnswers[] = $listAnswers;
        }

        dump($questions);
        dump($mixedAnswers);

        return $this->render('question/startQuiz.html.twig', [
            'questions' => $questions,
            'mixedAnswers' => $mixedAnswers,
            'quiz' => $quiz

        ]);
    }
}
