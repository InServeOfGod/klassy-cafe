<?php

namespace App\Controller;

use App\Entity\HeaderImages;
use App\Form\HeaderImagesType;
use App\Repository\HeaderImagesRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/header-images")
 * @IsGranted("ROLE_ADMIN")
*/
class HeaderImagesController extends AbstractController
{
    /**
     * @Route("/", name="app_header_images_index")
     */
    public function index(HeaderImagesRepository $headerImagesRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('header_images/index.html.twig', [
            'header_images' => $headerImagesRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Header'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_header_images_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, HeaderImages $headerImages, HeaderImagesRepository $headerImagesRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(HeaderImagesType::class, $headerImages);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('bg')->getData();
            $images_dir = $this->getParameter("images_dir");

            if ($file) {
                $filename = $fileUploader->upload($file);

                // if uploading is a success then set new filename of photo
                if ($filename !== null) {
                    $filename = $images_dir . $filename;
                    $headerImages->setBg($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $headerImagesRepository->add($headerImages, true);
            return $this->redirectToRoute('app_header_images_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('header_images/edit.html.twig', [
            'header_image' => $headerImages,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Header'
        ]);
    }
}
