<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    /**
     * @Route("/", name="front")
     * @param TrickRepository $trickRepository
     * @return Response
     */
    public function index(TrickRepository $trickRepository)
    {
        $nTrick = $trickRepository->count([]);
        $tricks = $trickRepository->findBy([], ['created' => 'desc'], 15, 0);

        return $this->render('front/index.html.twig', [
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

        return $this->render('front/moreTrick.html.twig', [
            'tricks' => $tricks,
        ]);
    }

    /**
     * @Route("/trick/details/{title}", name="details")
     * @param Request $request
     * @param Trick $trick
     * @return Response
     */
    public function trick(Request $request, Trick $trick, EntityManagerInterface $entityManager)
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            //$comment->setUser('1');

            $entityManager->persist($comment);
            $entityManager->flush();
            //return $this->redirectToRoute('task_success');
        }

        return $this->render('front/trick.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    public function showMoreComment()
    {

    }
}
