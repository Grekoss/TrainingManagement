<?php

namespace App\Controller\Teacher;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Form\QuestionType;
use App\Form\QuizType;
use Exception;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuizController extends TeacherController
{
    /**
     * @Route("/teacher/question/", name="teacher_quiz_index")
     *
     * @param Request               $request
     *
     * @return Response
     */
    public function indexQuiz(Request $request): Response
    {
        $pagination = $this->paginator->paginate(
            $this->quizRepository->findBy(array(), ['updatedAt' => 'DESC']),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('teacher/quiz/index.html.twig', [
            'listQuizzes' => $pagination
        ]);
    }

    /**
     * @Route("/teacher/question/{id}/results", name="teacher_quiz_showResults")
     *
     * @param Quiz                  $quiz
     * @param Request               $request
     *
     * @return Response
     */
    public function showResultsByQuiz(Quiz $quiz, Request $request): Response
    {
        $pagination = $this->paginator->paginate(
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
     * @Route("/teacher/question/{id}/fiche", name="teacher_quiz_fiche")
     *
     * @param Quiz  $quiz
     *
     * @return Response
     */
    public function quizFiche(Quiz $quiz): Response
    {
        return $this->render('teacher/quiz/showQuestion.html.twig', [
            'quiz' => $quiz
        ]);
    }

    /**
     * @Route("/teacher/question/{id}/fiche/edit", name="teacher_quiz_fiche_edit")
     *
     * @param Question  $question
     * @param Request   $request
     *
     * @return Response
     */
    public function quizFicheEdit(Question $question, Request $request): Response
    {
        $quizId = $question->getQuiz()->getId();

        if (!$this->isGranted('EDIT', $question)) {
            $this->addFlash('danger', 'Vous n\'avez pas access pour modifier cette question !');

            return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $quizId ]);
        }

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->manager->flush();

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
     * @Route("/teacher/question/{id}/fiche/new", name="teacher_quiz_fiche_new")
     *
     * @param Quiz      $quiz
     * @param Request   $request
     *
     * @return Response
     */
    public function quizFicheNew(Quiz $quiz, Request $request): Response
    {
        $question = new Question();

        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $question->setQuiz($quiz);

            $this->manager->persist($question);
            $this->manager->flush();

            $this->addFlash('success', 'Votre ajout a été validé');

            return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $quiz->getId()]);
        }

        return $this->render('teacher/quiz/newQuestion.html.twig', [
            'quiz' => $quiz->getId(),
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/teacher/question/{id}/fiche/delete", name="teacher_quiz_fiche_delete", methods="DELETE")
     *
     * @param Request   $request
     * @param Question  $question
     *
     * @return Response
     */
    public function quizDelete(Request $request, Question $question): Response
    {
        $quizId = $question->getQuiz()->getId();

        if (!$this->isGranted('EDIT', $question)) {
            $this->addFlash('danger', 'Vous n\'avez pas access pour supprimer cette question !');

            return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $quizId ]);
        }


        if ($this->isCsrfTokenValid('delete'.$question->getId(), $request->request->get('_token'))) {

            $this->manager->remove($question);
            $this->manager->flush();
        }

        $this->addFlash('warning', 'La suppression de la question a été faite !');

        return $this->redirectToRoute('teacher_quiz_fiche', ['id' => $quizId]);
    }

    /**
     * @Route("/teacher/question/{id}/update", name="teacher_quiz_update")
     *
     * @param Request   $request
     * @param Quiz      $quiz
     *
     * @return Response
     * @throws Exception
     */
    public function updateQuiz(Request $request, Quiz $quiz): Response
    {
        if (!$this->isGranted('EDIT', $quiz)) {
            $this->addFlash('danger', 'Vous n\'avez pas access pour modifier ce quiz !');

            return $this->redirectToRoute('teacher_quiz_index');
        }

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quiz->setUpdatedAt(new \DateTime());

            $this->manager->persist($quiz);
            $this->manager->flush();

            return $this->redirectToRoute('teacher_quiz_index');
        }

        return $this->render('teacher/quiz/editQuiz.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/teacher/question/create", name="teacher_quiz_create")
     *
     * @param Request   $request
     *
     * @return Response
     */
    public function createQuiz(Request $request): Response
    {
        $quiz = new Quiz();

        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $quiz->setAuthor($this->getUser());

            $this->manager->persist($quiz);
            $this->manager->flush();

            $this->addFlash('success', 'Quiz ajouté');

            return $this->redirectToRoute('teacher_quiz_index');
        }

        return $this->render('teacher/quiz/newQuiz.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/teacher/question/{id}/delete", name="teacher_quiz_delete", methods="DELETE")
     *
     * @param Request   $request
     * @param Quiz      $quiz
     *
     * @return Response
     */
    public function deleteQuiz(Request $request, Quiz $quiz): Response
    {
        if (!$this->isGranted('EDIT', $quiz)) {
            $this->addFlash('danger', 'Vous n\'avez pas access pour supprimer ce quiz !');

            return $this->redirectToRoute('teacher_quiz_index');
        }

        if ($this->isCsrfTokenValid('delete'.$quiz->getId(), $request->request->get('_token'))) {

            $this->manager->remove($quiz);
            $this->manager->flush();
        }

        $this->addFlash('warning', 'La suppression du quiz a été faite !');

        return $this->redirectToRoute('teacher_quiz_index');
    }
}
