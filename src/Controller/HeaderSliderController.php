<?php

namespace App\Controller;

use App\Entity\HeaderSlider;
use App\Form\PhotosType;
use App\Repository\HeaderSliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/header/slider")
 */
class HeaderSliderController extends AbstractController
{
    /**
     * @Route("/", name="app_header_slider_index", methods={"GET"})
     */
    public function index(HeaderSliderRepository $headerSliderRepository): Response
    {
        return $this->render('header_slider/index.html.twig', [
            'header_sliders' => $headerSliderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_header_slider_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HeaderSliderRepository $headerSliderRepository): Response
    {
        $headerSlider = new HeaderSlider();
        $form = $this->createForm(PhotosType::class, $headerSlider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $headerSliderRepository->add($headerSlider, true);

            return $this->redirectToRoute('app_header_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('header_slider/new.html.twig', [
            'header_slider' => $headerSlider,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_header_slider_show", methods={"GET"})
     */
    public function show(HeaderSlider $headerSlider): Response
    {
        return $this->render('header_slider/show.html.twig', [
            'header_slider' => $headerSlider,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_header_slider_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, HeaderSlider $headerSlider, HeaderSliderRepository $headerSliderRepository): Response
    {
        $form = $this->createForm(PhotosType::class, $headerSlider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $headerSliderRepository->add($headerSlider, true);

            return $this->redirectToRoute('app_header_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('header_slider/edit.html.twig', [
            'header_slider' => $headerSlider,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_header_slider_delete", methods={"POST"})
     */
    public function delete(Request $request, HeaderSlider $headerSlider, HeaderSliderRepository $headerSliderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$headerSlider->getId(), $request->request->get('_token'))) {
            $headerSliderRepository->remove($headerSlider, true);
        }

        return $this->redirectToRoute('app_header_slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
