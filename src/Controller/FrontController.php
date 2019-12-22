<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     * @param CategoryRepository $categoryRepository
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(CategoryRepository $categoryRepository, TrickRepository $trickRepository)
    {
        return $this->render('front/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'tricks' => $trickRepository->findAll()
        ]);
    }
}
