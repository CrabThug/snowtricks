<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TrickController extends AbstractController
{
    /**
     * @Route("/trick/details/{title}", name="details")
     * @param Request $request
     * @param Trick $trick
     * @param EntityManagerInterface $entityManager
     * @param $commentRepository
     * @return Response
     */
    public function trick(Request $request, Trick $trick, EntityManagerInterface $entityManager, CommentRepository $commentRepository)
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

        $comment = $commentRepository->findBy(['trick' => $trick], ['creation' => 'desc'], 10);

        return $this->render('trick/detail.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comment' => $comment,
            'nComment' => $commentRepository->count(['trick' => $trick])
        ]);
    }

    /**
     * @Route("/trick/details/{title}/pagination", name="pagination")
     * @param Request $request
     * @param Trick $trick
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function pagination(Request $request, Trick $trick, CommentRepository $commentRepository)
    {

        $start = $request->request->get('start');

        return $this->render('trick/moreComment.html.twig', [
            'comment' => $commentRepository->findBy(['trick' => $trick], ['creation' => 'desc'], 10, $start),
        ]);
    }

    /**
     * @Route("/ajouter-trick", name="trickAdd")
     * @return Response
     */
    public function trickAdd(Request $request, EntityManagerInterface $entityManager)
    {

        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$comment->setUser('1');
            $entityManager->persist($trick);
            $entityManager->flush();
            //return $this->redirectToRoute('task_success');
        }

        return $this->render('trick/trick.html.twig', [
            //'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }
}
