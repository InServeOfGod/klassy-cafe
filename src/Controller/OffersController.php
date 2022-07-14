<?php

namespace App\Controller;

use App\Entity\Offers;
use App\Form\TitleType;
use App\Repository\OffersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/offers")
 * @IsGranted("ROLE_ADMIN")
 * */
class OffersController extends AbstractController
{
    /**
     * @Route("/", name="app_field_offers_index")
     */
    public function index(OffersRepository $offersRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('offers/index.html.twig', [
            'offers' => $offersRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Offers'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_field_offers_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Offers $offers, OffersRepository $offersRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TitleType::class, $offers);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offersRepository->add($offers, true);
            return $this->redirectToRoute('app_field_offers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offers/edit.html.twig', [
            'offers' => $offers,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Offers'
        ]);
    }
}
