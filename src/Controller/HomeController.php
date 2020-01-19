<?php

namespace App\Controller;

use App\Repository\TrickRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository)
    {
        $nTrick = $trickRepository->count([]);
        $tricks = $trickRepository->findBy([], ['created' => 'desc'], 15, 0);

        return $this->render('home/index.html.twig', [
            'tricks' => $tricks,
            'nTricks' => $nTrick
        ]);
    }

    /**
     * @Route("/showMoreTrick", name="showMoreTrick")
     * @param $start
     * @param TrickRepository $trickRepository
     * @return string
     */
    public function showMoreTrick(TrickRepository $trickRepository, Request $request)
    {
        $start = $request->request->get('start');
        $tricks = $trickRepository->findBy([], ['created' => 'desc'], 15, $start);

        return $this->render('home/moreTrick.html.twig', [
            'tricks' => $tricks,
        ]);
    }
}
