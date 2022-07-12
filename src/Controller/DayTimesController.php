<?php

namespace App\Controller;

use App\Entity\DayTimes;
use App\Form\DayTimesType;
use App\Repository\DayTimesRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/day/times")
 */
class DayTimesController extends AbstractController
{
    /**
     * @Route("/", name="app_day_times_index", methods={"GET"})
     */
    public function index(DayTimesRepository $dayTimesRepository): Response
    {
        return $this->render('day_times/index.html.twig', [
            'day_times' => $dayTimesRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_day_times_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DayTimesRepository $dayTimesRepository): Response
    {
        $dayTime = new DayTimes();
        $form = $this->createForm(DayTimesType::class, $dayTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dayTimesRepository->add($dayTime, true);

            return $this->redirectToRoute('app_day_times_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('day_times/new.html.twig', [
            'day_time' => $dayTime,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_day_times_show", methods={"GET"})
     */
    public function show(DayTimes $dayTime): Response
    {
        return $this->render('day_times/show.html.twig', [
            'day_time' => $dayTime,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_day_times_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DayTimes $dayTime, DayTimesRepository $dayTimesRepository): Response
    {
        $form = $this->createForm(DayTimesType::class, $dayTime);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $dayTimesRepository->add($dayTime, true);

            return $this->redirectToRoute('app_day_times_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('day_times/edit.html.twig', [
            'day_time' => $dayTime,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_day_times_delete", methods={"POST"})
     */
    public function delete(Request $request, DayTimes $dayTime, DayTimesRepository $dayTimesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dayTime->getId(), $request->request->get('_token'))) {
            $dayTimesRepository->remove($dayTime, true);
        }

        return $this->redirectToRoute('app_day_times_index', [], Response::HTTP_SEE_OTHER);
    }
}
