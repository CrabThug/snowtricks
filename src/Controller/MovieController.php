<?php

namespace App\Controller;

use App\Entity\Movie;
use App\Form\MovieType;
use App\Service\MovieHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/movie")
 */
class MovieController extends AbstractController
{
    /**
     * @Route("/{id}/edit", name="movie_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Movie $movie
     * @return Response
     */
    public function edit(Request $request, Movie $movie, MovieHandler $movieHandler): Response
    {
        $form = $this->createForm(MovieType::class, $movie);
        $form->handleRequest($request);
        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                return $this->redirectToRoute('trick_show', ['slug' => $movie->getTrick()->getSlug()]);
            }
        }
        return $this->render('movie/edit.html.twig', [
            'movie' => $movie,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="movie_delete", methods={"DELETE", "GET"})
     * @param Request $request
     * @param Movie $movie
     * @return Response
     */
    public function delete(Request $request, Movie $movie): Response
    {
        if ($this->isCsrfTokenValid('delete' . $movie->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($movie);
            $entityManager->flush();
            $this->addFlash('success', 'la video a ete supprimée');
        } else {
            $this->addFlash('error', 'la video n\'a pas ete supprimée');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->render('flash.html.twig');
        }

        return $this->redirectToRoute('trick_show', ['slug' => $movie->getTrick()->getSlug()]);
    }
}
