<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Service\CommentHandler;
use App\Service\TrickHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trick")
 */
class TrickController extends BaseController
{
    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @param TrickHandler $trickHandler
     * @return Response
     */
    public function new(Request $request, TrickHandler $trickHandler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickHandler->handle($trick);

            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/new.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/{slug}/edit", name="trick_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param TrickHandler $trickHandler
     * @return Response
     */
    public
    function edit(Request $request, Trick $trick, TrickHandler $trickHandler): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $trickHandler->handle($trick);

            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="trick_show", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param CommentHandler $commentHandler
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function show(Request $request, Trick $trick, CommentHandler $commentHandler, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $commentHandler->handle($comment, $trick, $user);

            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comment' => $commentRepository->findBy(['trick' => $trick], ['creation' => 'desc'], 10),
            'user' => $this->getUser(),
            'nComment' => $commentRepository->count(['trick' => $trick])
        ]);
    }

    /**
     * @Route("/{slug}/pagination", name="trick_comment_pagination", methods={"POST"})
     * @param Request $request
     * @param Trick $trick
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public
    function pagination(Request $request, Trick $trick, CommentRepository $commentRepository): Response
    {
        $start = $request->request->get('start');

        return $this->render('trick/_pagination.html.twig', [
            'comment' => $commentRepository->findBy(['trick' => $trick], ['creation' => 'desc'], 10, $start),
        ]);
    }

    /**
     * @Route("/{id}", name="trick_delete", methods={"DELETE"})
     * @param Request $request
     * @param Trick $trick
     * @return Response
     */
    public
    function delete(Request $request, Trick $trick): Response
    {
        try {
            $this->denyAccessUnlessGranted('ROLE_USER');
            if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
                $this->entityManager->remove($trick);
                $this->entityManager->flush();

                $this->addFlash('success', 'Le trick a bien ete supprimé');
            }
        } catch (\Exception $exception) {
            $this->addFlash('success', 'Le trick n\'a pas pu etre supprimé');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->render('flash.html.twig');
        }
        return $this->redirectToRoute('home');
    }
}
