<?php

namespace App\Controller;

use App\Entity\About;
use App\Form\AboutType;
use App\Repository\AboutRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/about")
 * @IsGranted("ROLE_ADMIN")
 */
class AboutController extends AbstractController
{
    /**
     * @Route("/", name="app_field_about_index")
     */
    public function index(AboutRepository $aboutRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('about/index.html.twig', [
            'abouts' => $aboutRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'About',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_field_about_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, About $about, AboutRepository $aboutRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(AboutType::class, $about);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('video_bg')->getData();
            $images_dir = $this->getParameter("images_dir");

            if ($file) {
                $filename = $fileUploader->upload($file);

                // if uploading is a success then set new filename of photo
                if ($filename !== null) {
                    $filename = $images_dir . $filename;
                    $about->setVideoBg($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $aboutRepository->add($about, true);
            return $this->redirectToRoute('app_field_about_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('about/edit.html.twig', [
            'about' => $about,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'About & About Photos'
        ]);
    }
}
