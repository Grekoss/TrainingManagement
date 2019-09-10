<?php

namespace App\Controller;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Entity\Result;
use App\Form\ResultType;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\ResultRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function startQuiz(Quiz $quiz, QuestionRepository $questionRepository, Request $request)
    {
        $questions = $questionRepository->findByQuiz($quiz);

        //On mélange les réponses de chaque questions
        $mixedAnswers = array();
        for ( $i=0 ; $i<count($questions) ; $i++ ) {
            $listAnswers = [$questions[$i]->getProp1(), $questions[$i]->getProp2(), $questions[$i]->getProp3(), $questions[$i]->getProp4()];
            shuffle($listAnswers);
            $mixedAnswers[] = $listAnswers;
        }

        //On récupere les questions avec la correspondance de l'indexe avec l'ID
        $newArrayQuestions = array();
        for ( $i=0 ; $i<count($questions) ; $i++ ) {
            $newArrayQuestions[$questions[$i]->getId()] = $questions[$i];
        }

        if ($request->isMethod('post')) {

            // Récupere toutes les key dans du tableau newArrayQuestions
            $listKey = array_keys($newArrayQuestions);
            // On recherche la valeur la plus haute pour déterminer la dernière key
            $lastKey = max($listKey);

            $responses = array();
            for ( $i=0 ; $i< ($lastKey + 1) ; $i++ ) {
                if (array_key_exists($i, $newArrayQuestions)) {
                    $question = $newArrayQuestions[$i];

                    $responseUser = 'pas de réponse';
                    if (array_key_exists($i, $_POST)) {
                        $responseUser = $_POST[$i];
                    }


                    $isGood = false;
                    if ( array_key_exists($i, $_POST) && $_POST[$i] === $newArrayQuestions[$i]->getProp1()) {
                        $isGood = true;
                    }

                    $responses[] = [$question, $responseUser, $isGood];
                }
            }

            // Calculer le nombre de bonne réponses et le pourcentage en convertissant en entier
            $goodResponses = 0;
            for ( $i=0 ; $i<count($responses) ; $i++ ) {
                if ($responses[$i][2]) {
                    $goodResponses++;
                }
            }

            $percent = ( $goodResponses * 100 ) / count($questions);
            $percent = intval($percent);

            $result = new Result();
            $result->setScore($percent)
                ->setResponses($responses)
                ->setQuiz($quiz)
                ->setStudent($this->getUser());

            $em = $this->getDoctrine()->getManager();
            $em->persist($result);
            $em->flush();

            $this->addFlash(
                'success',
                'Votre score de ' . $percent . '% a été enregistré !'
            );

            return $this->redirectToRoute('app_question_show', ['id' => $quiz->getId()]);
        }

        return $this->render('question/startQuiz.html.twig', [
            'questions' => $questions,
            'mixedAnswers' => $mixedAnswers,
            'quiz' => $quiz

        ]);
    }
}
