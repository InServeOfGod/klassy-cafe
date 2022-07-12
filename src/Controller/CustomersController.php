<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Form\CustomersType;
use App\Repository\CustomersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/customers")
 * @IsGranted("ROLE_ADMIN")
 */
class CustomersController extends AbstractController
{
    /**
     * @Route("/", name="app_customers_index", methods={"GET"})
     */
    public function index(CustomersRepository $customersRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('customers/index.html.twig', [
            'customers' => $customersRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Customers'
        ]);
    }

    /**
     * @Route("/new", name="app_customers_new", methods={"GET", "POST"})
     */
    public function new(Request $request, CustomersRepository $customersRepository, EntityManagerInterface $entityManager): Response
    {
        $customer = new Customers();
        $form = $this->createForm(CustomersType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customersRepository->add($customer, true);

            return $this->redirectToRoute('app_customers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customers/new.html.twig', [
            'customer' => $customer,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Customers'
        ]);
    }

    /**
     * @Route("/{id}", name="app_customers_show", methods={"GET"})
     */
    public function show(Customers $customer, EntityManagerInterface $entityManager): Response
    {
        return $this->render('customers/show.html.twig', [
            'customer' => $customer,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Customers'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_customers_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Customers $customer, CustomersRepository $customersRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CustomersType::class, $customer);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $customersRepository->add($customer, true);

            return $this->redirectToRoute('app_customers_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('customers/edit.html.twig', [
            'customer' => $customer,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Customers'
        ]);
    }

    /**
     * @Route("/{id}", name="app_customers_delete", methods={"POST"})
     */
    public function delete(Request $request, Customers $customer, CustomersRepository $customersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$customer->getId(), $request->request->get('_token'))) {
            $customersRepository->remove($customer, true);
        }

        return $this->redirectToRoute('app_customers_index', [], Response::HTTP_SEE_OTHER);
    }
}
