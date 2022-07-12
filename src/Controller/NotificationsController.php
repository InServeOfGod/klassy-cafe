<?php

namespace App\Controller;

use App\Entity\Notifications;
use App\Form\NotificationsType;
use App\Repository\NotificationsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/notifications")
 * @IsGranted("ROLE_ADMIN")
 */
class NotificationsController extends AbstractController
{
    /**
     * @Route("/", name="app_notifications_index", methods={"GET"})
     */
    public function index(NotificationsRepository $notificationsRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('notifications/index.html.twig', [
            'contacts' => contacts($entityManager),
            'notifications' => $notificationsRepository->findAll(),
            'title' => 'Notifications'
        ]);
    }

    /**
//     * @Route("/new", name="app_notifications_new", methods={"GET", "POST"})
     */
    /*public function new(Request $request, NotificationsRepository $notificationsRepository, EntityManagerInterface $entityManager): Response
    {
        $notification = new Notifications();
        $form = $this->createForm(NotificationsType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notificationsRepository->add($notification, true);

            return $this->redirectToRoute('app_notifications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('notifications/new.html.twig', [
            'notification' => $notification,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => $notificationsRepository->findAll(),
            'title' => 'Notifications'
        ]);
    }*/

    /**
     * @Route("/{id}", name="app_notifications_show", methods={"GET"})
     */
    public function show(Notifications $notification, EntityManagerInterface $entityManager): Response
    {
        return $this->render('notifications/show.html.twig', [
            'notification' => $notification,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Notifications'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_notifications_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Notifications $notification, NotificationsRepository $notificationsRepository, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(NotificationsType::class, $notification);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $notificationsRepository->add($notification, true);

            return $this->redirectToRoute('app_notifications_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('notifications/edit.html.twig', [
            'notification' => $notification,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => $notificationsRepository->findAll(),
            'title' => 'Notifications'
        ]);
    }

    /**
     * @Route("/{id}", name="app_notifications_delete", methods={"POST"})
     */
    public function delete(Request $request, Notifications $notification, NotificationsRepository $notificationsRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$notification->getId(), $request->request->get('_token'))) {
            $notificationsRepository->remove($notification, true);
        }

        return $this->redirectToRoute('app_notifications_index', [], Response::HTTP_SEE_OTHER);
    }
}
