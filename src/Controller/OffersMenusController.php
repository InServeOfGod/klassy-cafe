<?php

namespace App\Controller;

use App\Entity\OffersMenus;
use App\Form\OffersMenusType;
use App\Repository\OffersMenusRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/offers-menus")
 * @IsGranted("ROLE_ADMIN")
 */
class OffersMenusController extends AbstractController
{
    /**
     * @Route("/", name="app_offers_menus_index", methods={"GET"})
     */
    public function index(OffersMenusRepository $offersMenusRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('offers_menus/index.html.twig', [
            'offers_menus' => $offersMenusRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Offers Menus'
        ]);
    }

    /**
     * @Route("/new", name="app_offers_menus_new", methods={"GET", "POST"})
     */
    public function new(Request $request, OffersMenusRepository $offersMenusRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $offersMenu = new OffersMenus();
        $form = $this->createForm(OffersMenusType::class, $offersMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('photo')->getData();
            $images_dir = $this->getParameter("images_dir");

            if ($file) {
                $filename = $fileUploader->upload($file);

                // if uploading is a success then set new filename of photo
                if ($filename !== null) {
                    $filename = $images_dir . $filename;
                    $offersMenu->setPhoto($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $offersMenusRepository->add($offersMenu, true);
            return $this->redirectToRoute('app_offers_menus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offers_menus/new.html.twig', [
            'offers_menu' => $offersMenu,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Offers Menus'
        ]);
    }

    /**
     * @Route("/{id}", name="app_offers_menus_show", methods={"GET"})
     */
    public function show(OffersMenus $offersMenu, EntityManagerInterface $entityManager): Response
    {
        return $this->render('offers_menus/show.html.twig', [
            'offers_menu' => $offersMenu,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Offers Menus'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_offers_menus_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, OffersMenus $offersMenu, OffersMenusRepository $offersMenusRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(OffersMenusType::class, $offersMenu);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('photo')->getData();
            $images_dir = $this->getParameter("images_dir");

            if ($file) {
                $filename = $fileUploader->upload($file);

                // if uploading is a success then set new filename of photo
                if ($filename !== null) {
                    $filename = $images_dir . $filename;
                    $offersMenu->setPhoto($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $offersMenusRepository->add($offersMenu, true);
            return $this->redirectToRoute('app_offers_menus_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offers_menus/edit.html.twig', [
            'offers_menu' => $offersMenu,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Offers Menus'
        ]);
    }

    /**
     * @Route("/{id}", name="app_offers_menus_delete", methods={"POST"})
     */
    public function delete(Request $request, OffersMenus $offersMenu, OffersMenusRepository $offersMenusRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$offersMenu->getId(), $request->request->get('_token'))) {
            $offersMenusRepository->remove($offersMenu, true);
            unlink($this->getParameter('public_dir').$offersMenu->getPhoto());
        }

        return $this->redirectToRoute('app_offers_menus_index', [], Response::HTTP_SEE_OTHER);
    }
}
