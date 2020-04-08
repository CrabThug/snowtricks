<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\ImageType;
use App\Repository\ImageRepository;
use App\Service\ImageHandler;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/image")
 */
class ImageController extends BaseController
{
    /**
     * @Route("/{id}/edit", name="image_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Image $image
     * @param ImageHandler $imageHandler
     * @return Response
     */
    public function edit(Request $request, Image $image, ImageHandler $imageHandler): Response
    {

        $form = $this->createForm(ImageType::class, $image);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageHandler->handle($image, $image->getTrick());
            return $this->redirectToRoute('trick_show', ['slug' => $image->getTrick()->getSlug()]);
        }

        return $this->render('image/edit.html.twig', [
            'image' => $image,
            'form' => $form->createView(),
        ]);

    }

    /**
     * @Route("/{id}/main", name="image_main", methods={"POST"})
     * @param Request $request
     * @param Image $image
     * @param ImageHandler $imageHandler
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function main(Request $request, Image $image, ImageHandler $imageHandler)
    {
        if ($this->isCsrfTokenValid('mainImg' . $image->getId(), $request->request->get('_token'))) {
            $image->setBool(TRUE);
            $imageHandler->handle($image, $image->getTrick());
            if ($request->isXmlHttpRequest()) {
                return $this->render('flash.html.twig');
            }
        }
        $this->addFlash('error', 'la page demandé ne semble pas exister.');
        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/{id}", name="image_delete", methods={"DELETE", "GET"})
     * @param Request $request
     * @param Image $image
     * @param ImageRepository $imageRepository
     * @return Response
     */
    public
    function delete(Request $request, Image $image, ImageRepository $imageRepository)
    {
        if ($this->isCsrfTokenValid('delete' . $image->getId(), $request->request->get('_token'))) {
            if ($imageRepository->count(['trick' => $image->getTrick()]) > 1) {
                if ($image->getBool()) {
                    /* @var Image $t */
                    $t = $imageRepository->findOneBy(['trick' => $image->getTrick()], ['id' => 'ASC']);
                    $t->setBool(TRUE);
                }
                $this->entityManager->remove($image);
                $this->entityManager->flush();
                $this->addFlash('success', 'L\'image a bien été supprimée');
            } else {
                $this->addFlash('error', 'Impossible de supprimer toutes les images d\'une figure, ajoutez en d\'abord une');
            }
        } else {
            $this->addFlash('error', 'L\'image n\'a pas pu etre supprimée');
        }
        if ($request->isXmlHttpRequest()) {
            return $this->render('flash.html.twig');
        }
        return $this->redirectToRoute('trick_show', [
            'slug' => $image->getTrick()->getSlug(),
        ]);
    }
}
