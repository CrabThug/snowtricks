<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\Movie;
use App\Entity\Trick;
use App\Entity\User;
use App\Form\CommentType;
use App\Form\TrickType;
use App\Repository\CommentRepository;
use App\Repository\ImageRepository;
use App\Service\FileUploader;
use App\Service\TrickHandler;
use App\Service\UrlExtract;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/trick")
 */
class TrickController extends BaseController
{
    /**
     * @Route("/new", name="trick_new", methods={"GET","POST"})
     * @param Request $request
     * @param FileUploader $fileUploader
     * @param UrlExtract $urlExtract
     * @param SluggerInterface $slugger
     * @param $imageRepository
     * @return Response
     */
    public function new(Request $request, FileUploader $fileUploader, UrlExtract $urlExtract, SluggerInterface $slugger): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var Image $image */
            foreach ($trick->getImages() as $image) {
                if (!$image->getId()) {
                    $imageName = $fileUploader->upload($image->getFile());

                    if (!$image->findOneBy(['bool' => 1])) {
                        $image->setBool(TRUE);
                    }
                    $image->setName($imageName);
                    $image->setTrick($trick);
                }
            }
            /** @var Movie $movie */
            foreach ($trick->getMovies() as $movie) {
                if (!$movie->getId()) {
                    $movie->setTrick($trick);
                    $movieEmbed = $urlExtract->extract($movie->getEmbed());
                    $movie->setEmbed($movieEmbed);
                }
            }
            $trick->setSlug($slugger->slug($trick->getTitle())->lower());
            $this->entityManager->persist($trick);
            $this->entityManager->flush();

            return $this->redirectToRoute('trick_show', [
                'slug' => $trick->getSlug()
            ]);
        }

        return $this->render('trick/new.html.twig', ['form' => $form->createView(),
            'trick' => $trick]);
    }

    /**
     * @Route("/{slug}", name="trick_show", methods={"GET","POST"})
     * @param Request $request
     * @param Trick $trick
     * @param EntityManagerInterface $entityManager
     * @param CommentRepository $commentRepository
     * @return Response
     */
    public
    function show(Request $request, Trick $trick, EntityManagerInterface $entityManager, CommentRepository $commentRepository): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();
            $comment->setTrick($trick);
            $comment->setUser($user);
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
    function pagination(Request $request, Trick $trick, CommentRepository $commentRepository)
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
            /*/** @var Movie $movie */
            /*foreach ($trick->getMovies() as $movie) {
                if (!$movie->getId()) {
                    $movie->setTrick($trick);
                    $movieEmbed = $urlExtract->extract($movie->getEmbed());
                    $movie->setEmbed($movieEmbed);
                    $this->entityManager->persist($movie);
                }*/

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
     * @Route("/{slug}", name="trick_delete", methods={"DELETE"})
     * @param Request $request
     * @param Trick $trick
     * @return Response
     */
    public
    function delete(Request $request, Trick $trick): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isCsrfTokenValid('delete' . $trick->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($trick);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("image-delete/{id}", name="image_delete", methods={"DELETE"})
     * @param Request $request
     * @param Image $image
     * @return Response
     */
    public
    function deleteImage(Request $request, Image $image): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($image);
            $this->entityManager->flush();
        }
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("movie-delete/{id}", name="movie_delete", methods={"DELETE"})
     * @param Request $request
     * @param Movie $movie
     * @return Response
     */
    public
    function deleteMovie(Request $request, Movie $movie): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
            $this->entityManager->remove($movie);
            $this->entityManager->flush();
        }

        return $this->redirectToRoute('home');
    }
}
