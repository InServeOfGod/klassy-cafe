<?php

namespace App\Controller;

use App\Form\CofferType;
use App\Repository\CofferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/coffer")
 * @IsGranted("ROLE_ADMIN")
 */
class CofferController extends AbstractController
{
    /**
     * @Route("/", name="app_coffer")
     */
    public function index(Request $request, EntityManagerInterface $entityManager, CofferRepository $cofferRepository): Response
    {
        $cofferEntity = $cofferRepository->findOneBy([]);
        $form = $this->createForm(CofferType::class, $cofferEntity);
        $form->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {
            $cofferRepository->add($cofferEntity, true);
            return $this->redirectToRoute("app_coffer");
        }

        return $this->render('coffer/index.html.twig', [
            'controller_name' => 'CofferController',
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Case',
            'form' => $form->createView()
        ]);
    }
}
