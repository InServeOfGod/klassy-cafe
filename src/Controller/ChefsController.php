<?php

namespace App\Controller;

use App\Entity\Chefs;
use App\Form\TitleType;
use App\Repository\ChefsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/chefs")
 * @IsGranted("ROLE_ADMIN")
 */
class ChefsController extends AbstractController
{
    /**
     * @Route("/", name="app_field_chefs_index")
     */
    public function index(ChefsRepository $chefsRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('chefs/index.html.twig', [
            'chefs' => $chefsRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Chefs'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_field_chefs_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Chefs $chefs, ChefsRepository $chefsRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TitleType::class, $chefs);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $chefsRepository->add($chefs, true);
            return $this->redirectToRoute('app_field_chefs_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chefs/edit.html.twig', [
            'chefs' => $chefs,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Chefs & Chefs Slider'
        ]);
    }
}
