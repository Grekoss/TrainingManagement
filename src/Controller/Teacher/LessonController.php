<?php

namespace App\Controller\Teacher;

use App\Entity\Lesson;
use App\Form\LessonType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/teacher/lesson")
 */
class LessonController extends AbstractController
{
    /**
     * @Route("/lesson/new", name="teacher_new_lesson", methods="GET|POST")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function newLesson(Request $request): Response
    {
        if (!$this->isGranted('ROLE_TEACHER')) {
            $this->addFlash('danger', 'Access Interdit !');

            return $this->redirectToRoute('app_dashboard');
        }

        $lesson = new Lesson();
        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ) {

            $file = $form->get('file')->getData();
            // Téléchargement + stockage du nom du fichier
            if ($file === null) {
                $this->addFlash('danger', 'Vous devez ajouter un fichier !');

                return $this->redirectToRoute('teacher_new_lesson');
            }

            $lesson->setCreateBy($this->getUser());

            $this->getDoctrine()->getManager()->persist($lesson);
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre enregistrement a été validé, il est maintenant disponible !');

            return $this->redirectToRoute('app_lesson');
        }

        return $this->render('teacher/lesson/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lesson/{id}/update", name="teacher_lesson_update", methods="GET|POST")
     *
     * @param Request   $request
     * @param Lesson    $lesson
     *
     * @return RedirectResponse|Response
     */
    public function lessonUpdate(Request $request, Lesson $lesson): Response
    {
        // Utilisation du Voter
        if (!$this->isGranted('EDIT', $lesson)) {
            $this->addFlash('danger', 'Vous n\'avez pas access pour modifier cette leçon !');

            return $this->redirectToRoute('app_lesson');
        }

        $form = $this->createForm(LessonType::class, $lesson);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Mise à jour réussite');

            return $this->redirectToRoute('app_lesson');
        }

        return $this->render('teacher/lesson/edit.html.twig', [
            'lesson' => $lesson,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lesson/{id}/delete", name="teacher_lesson_delete", methods="DELETE")
     *
     * @param Request   $request
     * @param Lesson    $lesson
     *
     * @return Response|RedirectResponse
     */
    public function lessonDelete(Request $request, Lesson $lesson): Response
    {
        // Utilisation du Voter
        if (!$this->isGranted('EDIT', $lesson)) {
            $this->addFlash('danger', 'Vous n\'avez pas access pour supprimer cette leçon !');

            return $this->redirectToRoute('app_lesson');
        }

        if ($this->isCsrfTokenValid('delete'.$lesson->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($lesson);
            $em->flush();
        }

        $this->addFlash('warning', 'La suppression a bien été faite !');

        return $this->redirectToRoute('app_lesson');
    }
}
