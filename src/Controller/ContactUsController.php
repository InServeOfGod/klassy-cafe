<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
*/
class ContactUsController extends AbstractController
{
    /**
     * @Route("/contact-us", name="app_contact_us")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        return $this->render('contact_us/index.html.twig', [
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Contact Us'
        ]);
    }
}
