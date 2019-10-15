<?php

namespace App\Controller\Teacher;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use App\Repository\QuizRepository;
use App\Repository\ResultRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teacher/question")
 */
class QuizController extends AbstractController
{
    private $questionRepository;
    private $quizRepository;
    private $resultRepository;

    public function __construct(QuestionRepository $questionRepository, QuizRepository $quizRepository, ResultRepository $resultRepository)
    {
        $this->questionRepository = $questionRepository;
        $this->quizRepository = $quizRepository;
        $this->resultRepository = $resultRepository;
    }

    /**
     * @Route("/", name="teacher_quiz_index")
     *
     * @return Response
     */
    public function index(Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $this->quizRepository->findBy(array(), ['updatedAt' => 'DESC']),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('teacher/quiz/index.html.twig', [
            'listQuizzes' => $pagination
        ]);
    }

    /**
     * @Route("/{id}/results", name="teacher_quiz_showResults")
     */
    public function showResultsByQuiz(Quiz $quiz, Request $request, PaginatorInterface $paginator): Response
    {
        $pagination = $paginator->paginate(
            $this->resultRepository->findByQuiz($quiz),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('teacher/listResults.html.twig',[
            'results' => $pagination,
            'quiz' => $quiz,
            'byQuiz' => true
        ]);
    }

    /**
     * @Route("/{id}/fiche", name="teacher_quiz_fiche")
     */
    public function quizFiche(Quiz $quiz)
    {
        return $this->render('teacher/quiz/showQuestion.html.twig', [
            'quiz' => $quiz
        ]);
    }

    /**
     * @Route("/{id}/fiche/edit", name="teacher_quiz_fiche_edit")
     */
    public function quizFicheEdit(Question $question, Request $request)
    {
        $quizId = $question->getQuiz()->getId();

        if (!$this->isGranted('EDIT', $question)) {
            $this->addFlash('danger', 'Vous n\'avez pas access pour modifier cette question !');

            return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $quizId ]);
        }

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Mise à jour réussite');

            return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $question->getQuiz()->getId()]);
        }

        return $this->render('teacher/quiz/editQuestion.html.twig', [
            'question' => $question,
            'quiz' => $quizId,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/fiche/new", name="teacher_quiz_fiche_new")
     */
    public function quizFicheNew(Quiz $quiz, Request $request)
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setQuiz($quiz);

            $em = $this->getDoctrine()->getManager();
            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Votre ajout a été validé');

            return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $quiz->getId()]);
        }

        return $this->render('teacher/quiz/newQuestion.html.twig', [
            'quiz' => $quiz->getId(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{id}/fiche/delete", name="teacher_quiz_fiche_delete", methods="DELETE")
     */
    public function quizDelete(Request $request, Question $question): Response
    {
        $quizId = $question->getQuiz()->getId();

        if (!$this->isGranted('EDIT', $question)) {
            $this->addFlash('danger', 'Vous n\'avez pas access pour supprimer cette question !');

            return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $quizId ]);
        }


        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($question);
            $em->flush();
        }

        $this->addFlash('warning', 'La suppression de la question a été faite !');

        return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $quizId]);
    }

//    public function updateQuiz
    
//    public function createQuiz

//    public function deleteQuiz
}
