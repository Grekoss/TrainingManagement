<?php

namespace App\Controller;

use App\Repository\LessonRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class LessonController extends AbstractController
{
    /**
     * @Route("/lesson", name="app_lesson")
     */
    public function index(LessonRepository $lessonRepository)
    {
        $listFoodSafety = $lessonRepository->findByCategory('Sécurité Alimentaire');
        $listManagement = $lessonRepository->findByCategory('Gestion');
        $listCounter = $lessonRepository->findByCategory('Comptoir');
        $listKitchen = $lessonRepository->findByCategory('Cuisine');
        $listPEP = $lessonRepository->findByCategory('PEP');
        $listAdministration = $lessonRepository->findByCategory('Administration');
        $listVarious = $lessonRepository->findByCategory('Divers');

        return $this->render('lesson/index.html.twig', [
            'listFoodSafety' => $listFoodSafety,
            'listManagement' => $listManagement,
            'listCounter' => $listCounter,
            'listKitchen' => $listKitchen,
            'listPEP' => $listPEP,
            'listAdministration' => $listAdministration,
            'listVarious' => $listVarious
        ]);
    }

    /**
     * @Route("lesson/{slug}", name="app_lesson_category")
     */
    public function showByCategory($slug, LessonRepository $lessonRepository)
    {
        $list = [];
        $title = null;

        switch ($slug) {
            case 'food-safty' :
                $list = $lessonRepository->findByCategory('Sécurité Alimentaire');
                $title = 'Sécurité Alimentaire';
                break;
            case 'management' :
                $list = $lessonRepository->findByCategory('Gestion');
                $title = 'Gestion de quart';
                break;
            case 'counter' :
                $list = $lessonRepository->findByCategory('Comptoir');
                $title = 'Comptoir';
                break;
            case 'kitchen' :
                $list = $lessonRepository->findByCategory('Cuisine');
                $title = 'Cuisine';
                break;
            case 'PEP' :
                $list = $lessonRepository->findByCategory('PEP');
                $title = 'PEP';
                break;
            case 'administration' :
                $list = $lessonRepository->findByCategory('Administration');
                $title = 'Administration';
                break;
            case 'various' :
                $list = $lessonRepository->findByCategory('Divers');
                $title = 'Divers';
                break;
            default:
                $list = null;
                break;
        }

        return $this->render('lesson/show.html.twig', [
            'slug' => $slug,
            'title' => $title,
            'list' => $list
        ]);
    }
}
