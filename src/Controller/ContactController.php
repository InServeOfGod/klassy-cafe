<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/contact")
 * @IsGranted("ROLE_ADMIN")
 */
class ContactController extends AbstractController
{
    /**
     * @Route("/", name="app_contact_index", methods={"GET"})
     */
    public function index(ContactRepository $contactRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('contact/index.html.twig', [
            'contacts' => $contactRepository->findAll(),
            'notifications' => notify($entityManager),
            'title' => 'Reservations & Contacting'
        ]);
    }

    /**
//     * @Route("/new", name="app_contact_new", methods={"GET", "POST"})
     */
    /*public function new(Request $request, ContactRepository $contactRepository, EntityManagerInterface $entityManager): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact, true);

            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/new.html.twig', [
            'contact' => $contact,
            'form' => $form,
            'contacts' => $contactRepository->findAll(),
            'notifications' => notify($entityManager),
            'title' => 'Reservations & Contacting'
        ]);
    }*/

    /**
     * @Route("/{id}", name="app_contact_show", methods={"GET"})
     */
    public function show(Contact $contact, EntityManagerInterface $entityManager): Response
    {
        return $this->render('contact/show.html.twig', [
            'contact' => $contact,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Reservations & Contacting'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_contact_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Contact $contact, ContactRepository $contactRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactRepository->add($contact, true);

            return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact/edit.html.twig', [
            'contact' => $contact,
            'form' => $form,
            'contacts' => $contactRepository->findAll(),
            'notifications' => notify($entityManager),
            'title' => 'Reservations & Contacting'
        ]);
    }

    /**
     * @Route("/{id}", name="app_contact_delete", methods={"POST"})
     */
    public function delete(Request $request, Contact $contact, ContactRepository $contactRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$contact->getId(), $request->request->get('_token'))) {
            $contactRepository->remove($contact, true);
        }

        return $this->redirectToRoute('app_contact_index', [], Response::HTTP_SEE_OTHER);
    }
}
