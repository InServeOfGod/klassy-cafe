<?php

namespace App\Controller;

use App\Entity\ContactUs;
use App\Form\ContactUsType;
use App\Repository\ContactUsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/contact-us")
 * @IsGranted("ROLE_ADMIN")
*/
class ContactUsController extends AbstractController
{
    /**
     * @Route("/", name="app_contact_us_index")
     */
    public function index(ContactUsRepository $contactUsRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('contact_us/index.html.twig', [
            'contactUs' => $contactUsRepository->findOneBy([]),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Contact Us',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_contact_us_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ContactUs $contactUs, ContactUsRepository $contactUsRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ContactUsType::class, $contactUs);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $contactUsRepository->add($contactUs, true);

            return $this->redirectToRoute('app_contact_us_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('contact_us/edit.html.twig', [
            'contact' => $contactUs,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Reservations & Contacting'
        ]);
    }
}
