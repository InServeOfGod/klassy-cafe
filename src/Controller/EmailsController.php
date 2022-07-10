<?php

namespace App\Controller;

use App\Entity\Emails;
use App\Form\EmailsType;
use App\Repository\EmailsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/emails")
 * @IsGranted("ROLE_ADMIN")
 */
class EmailsController extends AbstractController
{
    /**
     * @Route("/new", name="app_emails_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EmailsRepository $emailsRepository, EntityManagerInterface $entityManager): Response
    {
        $email = new Emails();
        $form = $this->createForm(EmailsType::class, $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailsRepository->add($email, true);

            return $this->redirectToRoute('app_settings', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emails/new.html.twig', [
            'email' => $email,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Add New Phone Number',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_emails_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Emails $email, EmailsRepository $emailsRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EmailsType::class, $email);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $emailsRepository->add($email, true);

            return $this->redirectToRoute('app_settings', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('emails/edit.html.twig', [
            'email' => $email,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Add New Phone Number',
        ]);
    }

    /**
     * @Route("/{id}", name="app_emails_delete", methods={"POST"})
     */
    public function delete(Request $request, Emails $email, EmailsRepository $emailsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$email->getId(), $request->request->get('_token'))) {
            $emailsRepository->remove($email, true);
        }

        return $this->redirectToRoute('app_settings', [], Response::HTTP_SEE_OTHER);
    }
}
