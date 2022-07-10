<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Notifications;
use App\Entity\PhoneNumbers;
use App\Form\PhoneNumbersType;
use App\Repository\PhoneNumbersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/phone_numbers")
 * @IsGranted("ROLE_ADMIN")
 */
class PhoneNumbersController extends AbstractController
{
    /**
     * @Route("/new", name="app_phone_numbers_new", methods={"GET", "POST"})
     */
    public function new(Request $request, PhoneNumbersRepository $phoneNumbersRepository, EntityManagerInterface $entityManager): Response
    {
        $phoneNumber = new PhoneNumbers();
        $form = $this->createForm(PhoneNumbersType::class, $phoneNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phoneNumbersRepository->add($phoneNumber, true);

            return $this->redirectToRoute('app_settings', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('phone_numbers/new.html.twig', [
            'phone_number' => $phoneNumber,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Add New Phone Number',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_phone_numbers_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, PhoneNumbers $phoneNumber, PhoneNumbersRepository $phoneNumbersRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(PhoneNumbersType::class, $phoneNumber);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $phoneNumbersRepository->add($phoneNumber, true);

            return $this->redirectToRoute('app_settings', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('phone_numbers/edit.html.twig', [
            'phone_number' => $phoneNumber,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Edit Phone Number',
        ]);
    }

    /**
     * @Route("/{id}", name="app_phone_numbers_delete", methods={"POST"})
     */
    public function delete(Request $request, PhoneNumbers $phoneNumber, PhoneNumbersRepository $phoneNumbersRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$phoneNumber->getId(), $request->request->get('_token'))) {
            $phoneNumbersRepository->remove($phoneNumber, true);
        }

        return $this->redirectToRoute('app_settings', [], Response::HTTP_SEE_OTHER);
    }
}
