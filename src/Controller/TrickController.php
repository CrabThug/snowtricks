<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Movie;
use App\Entity\Trick;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/trick")
 */
class TrickController extends AbstractController
{
    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @param $fileUploader
     * @return Response
     */
    public function new(Request $request, FileUploader $fileUploader): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($images = $form->get('images')->getData()) {
                /** @var Image $image */
                foreach ($images as $image) {
                    $imageName = $fileUploader->upload($image->getFile());
                    $image->setName($imageName);
                    $image->setTrick($trick);
                }
            }
            if ($movies = $form->get('movies')->getData()) {
                /** @var Movie $movie */
                foreach ($movies as $movie) {
                    $movieName = $fileUploader->upload($movie->getFile());
                    $movie->setName($movieName);
                    $movie->setTrick($trick);
                }
            }
            $trick->setSlug(strtolower(str_replace(' ', '-', $trick->getTitle())));
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($trick);
            $entityManager->flush();

            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="trick_show", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param EntityManagerInterface $entityManager
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public function show(Request $request, Trick $trick, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setTrick($trick);
            //$comment->setUser('1'); //TODO add authentication

            $entityManager->persist($comment);
            $entityManager->flush();
            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug()
            ]);
        }
        $comment = $commentRepository->findBy(['trick' => $trick], ['creation' => 'desc'], 10);

        return $this->render('trick/show.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
            'comment' => $comment,
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
    public function pagination(Request $request, Trick $trick, CommentRepository $commentRepository)
    {
        $start = $request->request->get('start');

        return $this->render('trick/_pagination.html.twig', [
            'comment' => $commentRepository->findBy(['trick' => $trick], ['creation' => 'desc'], 10, $start),
        ]);
    }

    /**
     * @Route("/{slug}/edit", name="trick_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param $fileUploader
     * @return Response
     */
    public function edit(Request $request, Trick $trick, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            if ($images = $form->get('images')->getData()) {
                /** @var Image $image */
                foreach ($images as $image) {
                    $imageName = $fileUploader->upload($image->getFile());
                    $image->setName($imageName);
                    $image->setTrick($trick);
                }
            }
            if ($movies = $form->get('movies')->getData()) {
                /** @var Movie $movie */
                foreach ($movies as $movie) {
                    $movieName = $fileUploader->upload($movie->getFile());
                    $movie->setName($movieName);
                    $movie->setTrick($trick);
                }
            }

            $trick->setSlug(strtolower(str_replace(' ', '-', $trick->getTitle())));


            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('home');
        }

        return $this->render('trick/edit.html.twig', [
            'trick' => $trick,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{slug}", name="trick_delete", methods={"DELETE"})
     * @param Request $request
     * @param Trick $trick
     * @return Response
     */
    public function delete(Request $request, Trick $trick): Response
    {
        if ($this->isCsrfTokenValid('delete'.$trick->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($trick);
            $entityManager->flush();
        }
        return $this->redirectToRoute('home');
    }
}