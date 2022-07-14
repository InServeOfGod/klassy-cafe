<?php

namespace App\Controller;

use App\Entity\HeaderSlider;
use App\Form\PhotosType;
use App\Repository\HeaderSliderRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/header-slider")
 * @IsGranted("ROLE_ADMIN")
 */
class HeaderSliderController extends AbstractController
{
    /**
     * @Route("/", name="app_header_slider_index", methods={"GET"})
     */
    public function index(HeaderSliderRepository $headerSliderRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('header_slider/index.html.twig', [
            'header_sliders' => $headerSliderRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Header & Header Sliders'
        ]);
    }

    /**
     * @Route("/new", name="app_header_slider_new", methods={"GET", "POST"})
     */
    public function new(Request $request, HeaderSliderRepository $headerSliderRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $headerSlider = new HeaderSlider();
        $form = $this->createForm(PhotosType::class, $headerSlider);
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
                    $headerSlider->setPhoto($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $headerSliderRepository->add($headerSlider, true);
            return $this->redirectToRoute('app_header_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('header_slider/new.html.twig', [
            'header_slider' => $headerSlider,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Header & Header Sliders'
        ]);
    }

    /**
     * @Route("/{id}", name="app_header_slider_show", methods={"GET"})
     */
    public function show(HeaderSlider $headerSlider, EntityManagerInterface $entityManager): Response
    {
        return $this->render('header_slider/show.html.twig', [
            'header_slider' => $headerSlider,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Header & Header Sliders'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_header_slider_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, HeaderSlider $headerSlider, HeaderSliderRepository $headerSliderRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(PhotosType::class, $headerSlider);
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
                    $headerSlider->setPhoto($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $headerSliderRepository->add($headerSlider, true);
            return $this->redirectToRoute('app_header_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('header_slider/edit.html.twig', [
            'header_slider' => $headerSlider,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Header & Header Sliders'
        ]);
    }

    /**
     * @Route("/{id}", name="app_header_slider_delete", methods={"POST"})
     */
    public function delete(Request $request, HeaderSlider $headerSlider, HeaderSliderRepository $headerSliderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$headerSlider->getId(), $request->request->get('_token'))) {
            $headerSliderRepository->remove($headerSlider, true);
            unlink($this->getParameter('public_dir').$headerSlider->getPhoto());
        }

        return $this->redirectToRoute('app_header_slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
