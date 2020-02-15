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
    public function index(TrickRepository $trickRepository): Response
    {
        return $this->render('home/index.html.twig', [
            'tricks' => $trickRepository->findBy([], ['created' => 'desc'], 15, 0),
            'nTricks' => $trickRepository->count([])
        ]);
    }

    /**
     * @Route("/show-more-trick", name="showMoreTrick")
     * @param $start
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function showMoreTrick(TrickRepository $trickRepository, Request $request)
    {
        $start = $request->request->get('start');

        return $this->render('home/trick.html.twig', [
            'tricks' => $trickRepository->findBy([], ['created' => 'desc'], 15, $start),
        ]);
    }
}
