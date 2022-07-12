<?php

namespace App\Controller;

use App\Entity\ChefsSlider;
use App\Form\ChefsSliderType;
use App\Repository\ChefsSliderRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/chefs-slider")
 * @IsGranted("ROLE_ADMIN")
 */
class ChefsSliderController extends AbstractController
{
    /**
     * @Route("/", name="app_chefs_slider_index", methods={"GET"})
     */
    public function index(ChefsSliderRepository $chefsSliderRepository): Response
    {
        return $this->render('chefs_slider/index.html.twig', [
            'chefs_sliders' => $chefsSliderRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_chefs_slider_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ChefsSliderRepository $chefsSliderRepository): Response
    {
        $chefsSlider = new ChefsSlider();
        $form = $this->createForm(ChefsSliderType::class, $chefsSlider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chefsSliderRepository->add($chefsSlider, true);

            return $this->redirectToRoute('app_chefs_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chefs_slider/new.html.twig', [
            'chefs_slider' => $chefsSlider,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_chefs_slider_show", methods={"GET"})
     */
    public function show(ChefsSlider $chefsSlider): Response
    {
        return $this->render('chefs_slider/show.html.twig', [
            'chefs_slider' => $chefsSlider,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_chefs_slider_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ChefsSlider $chefsSlider, ChefsSliderRepository $chefsSliderRepository): Response
    {
        $form = $this->createForm(ChefsSliderType::class, $chefsSlider);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chefsSliderRepository->add($chefsSlider, true);

            return $this->redirectToRoute('app_chefs_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chefs_slider/edit.html.twig', [
            'chefs_slider' => $chefsSlider,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_chefs_slider_delete", methods={"POST"})
     */
    public function delete(Request $request, ChefsSlider $chefsSlider, ChefsSliderRepository $chefsSliderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chefsSlider->getId(), $request->request->get('_token'))) {
            $chefsSliderRepository->remove($chefsSlider, true);
        }

        return $this->redirectToRoute('app_chefs_slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
