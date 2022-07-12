<?php

namespace App\Controller;

use App\Entity\Profits;
use App\Form\ProfitsType;
use App\Repository\ProfitsRepository;
use DateTime;
use Doctrine\DBAL\Exception\ConstraintViolationException;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/profits")
 * @IsGranted("ROLE_ADMIN")
 */
class ProfitsController extends AbstractController
{
    /**
     * @Route("/", name="app_profits_index", methods={"GET"})
     */
    public function index(ProfitsRepository $profitsRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('profits/index.html.twig', [
            'profits' => $profitsRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Profits'
        ]);
    }

    /**
     * @Route("/new", name="app_profits_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ProfitsRepository $profitsRepository, EntityManagerInterface $entityManager): Response
    {
        $profit = new Profits();
        $profit->setProfitDate(new DateTime("now"));

        $form = $this->createForm(ProfitsType::class, $profit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profitsRepository->add($profit, true);
            return $this->redirectToRoute('app_profits_index', [], Response::HTTP_SEE_OTHER);
        }

        if ($form->isSubmitted() && !$form->isValid()) {
            $this->addFlash('warning', "You can not add new profit on same date. Please try to edit today's record if you want to.");
        }

        return $this->renderForm('profits/new.html.twig', [
            'profit' => $profit,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Profits'
        ]);
    }

    /**
     * @Route("/{id}", name="app_profits_show", methods={"GET"})
     */
    public function show(Profits $profit, EntityManagerInterface $entityManager): Response
    {
        return $this->render('profits/show.html.twig', [
            'profit' => $profit,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Profits'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_profits_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Profits $profit, ProfitsRepository $profitsRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProfitsType::class, $profit);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profitsRepository->add($profit, true);

            return $this->redirectToRoute('app_profits_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('profits/edit.html.twig', [
            'profit' => $profit,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Profits'
        ]);
    }

    /**
     * @Route("/{id}", name="app_profits_delete", methods={"POST"})
     */
    public function delete(Request $request, Profits $profit, ProfitsRepository $profitsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$profit->getId(), $request->request->get('_token'))) {
            $profitsRepository->remove($profit, true);
        }

        return $this->redirectToRoute('app_profits_index', [], Response::HTTP_SEE_OTHER);
    }
}
