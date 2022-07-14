<?php

namespace App\Controller;

use App\Entity\ChefsSlider;
use App\Form\ChefsSliderType;
use App\Repository\ChefsSliderRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/chefs-slider")
 * @IsGranted("ROLE_ADMIN")
 */
class ChefsSliderController extends AbstractController
{
    /**
     * @Route("/", name="app_chefs_slider_index", methods={"GET"})
     */
    public function index(ChefsSliderRepository $chefsSliderRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('chefs_slider/index.html.twig', [
            'chefs_sliders' => $chefsSliderRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Chefs Slider'
        ]);
    }

    /**
     * @Route("/new", name="app_chefs_slider_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ChefsSliderRepository $chefsSliderRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $chefsSlider = new ChefsSlider();
        $form = $this->createForm(ChefsSliderType::class, $chefsSlider);
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
                    $chefsSlider->setPhoto($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $chefsSliderRepository->add($chefsSlider, true);
            return $this->redirectToRoute('app_chefs_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chefs_slider/new.html.twig', [
            'chefs_slider' => $chefsSlider,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Chefs Slider'
        ]);
    }

    /**
     * @Route("/{id}", name="app_chefs_slider_show", methods={"GET"})
     */
    public function show(ChefsSlider $chefsSlider, EntityManagerInterface $entityManager): Response
    {
        return $this->render('chefs_slider/show.html.twig', [
            'chefs_slider' => $chefsSlider,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Chefs Slider'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_chefs_slider_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ChefsSlider $chefsSlider, ChefsSliderRepository $chefsSliderRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(ChefsSliderType::class, $chefsSlider);
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
                    $chefsSlider->setPhoto($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $chefsSliderRepository->add($chefsSlider, true);
            return $this->redirectToRoute('app_chefs_slider_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('chefs_slider/edit.html.twig', [
            'chefs_slider' => $chefsSlider,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Chefs Slider'
        ]);
    }

    /**
     * @Route("/{id}", name="app_chefs_slider_delete", methods={"POST"})
     */
    public function delete(Request $request, ChefsSlider $chefsSlider, ChefsSliderRepository $chefsSliderRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$chefsSlider->getId(), $request->request->get('_token'))) {
            $chefsSliderRepository->remove($chefsSlider, true);
            unlink($this->getParameter('public_dir').$chefsSlider->getPhoto());
        }

        return $this->redirectToRoute('app_chefs_slider_index', [], Response::HTTP_SEE_OTHER);
    }
}
