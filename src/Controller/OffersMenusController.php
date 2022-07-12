<?php

namespace App\Controller;

use App\Entity\OffersMenus;
use App\Form\OffersMenusType;
use App\Repository\OffersMenusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/offers-menus")
 * @IsGranted("ROLE_ADMIN")
 */
class OffersMenusController extends AbstractController
{
    /**
     * @Route("/", name="app_offers_menus_index", methods={"GET"})
     */
    public function index(OffersMenusRepository $offersMenusRepository): Response
    {
        return $this->render('offers_menus/index.html.twig', [
            'offers_menuses' => $offersMenusRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_offers_menus_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OffersMenusRepository $offersMenusRepository): Response
    {
        $offersMenu = new OffersMenus();
        $form = $this->createForm(OffersMenusType::class, $offersMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offersMenusRepository->add($offersMenu, true);

            return $this->redirectToRoute('app_offers_menus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offers_menus/new.html.twig', [
            'offers_menu' => $offersMenu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_offers_menus_show", methods={"GET"})
     */
    public function show(OffersMenus $offersMenu): Response
    {
        return $this->render('offers_menus/show.html.twig', [
            'offers_menu' => $offersMenu,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_offers_menus_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, OffersMenus $offersMenu, OffersMenusRepository $offersMenusRepository): Response
    {
        $form = $this->createForm(OffersMenusType::class, $offersMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offersMenusRepository->add($offersMenu, true);

            return $this->redirectToRoute('app_offers_menus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offers_menus/edit.html.twig', [
            'offers_menu' => $offersMenu,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_offers_menus_delete", methods={"POST"})
     */
    public function delete(Request $request, OffersMenus $offersMenu, OffersMenusRepository $offersMenusRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offersMenu->getId(), $request->request->get('_token'))) {
            $offersMenusRepository->remove($offersMenu, true);
        }

        return $this->redirectToRoute('app_offers_menus_index', [], Response::HTTP_SEE_OTHER);
    }
}
