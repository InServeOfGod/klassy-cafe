<?php

namespace App\Controller;

use App\Entity\AboutPhotos;
use App\Form\PhotosType;
use App\Repository\AboutPhotosRepository;
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
 * @Route("admin/about-photos")
 * @IsGranted("ROLE_ADMIN")
 */
class AboutPhotosController extends AbstractController
{
    /**
     * @Route("/", name="app_about_photos_index", methods={"GET"})
     */
    public function index(AboutRepository $aboutRepository, AboutPhotosRepository $aboutPhotosRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('about_photos/index.html.twig', [
            'about' => $aboutRepository->findOneBy([]),
            'about_photos' => $aboutPhotosRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'About & About Photos'
        ]);
    }

    /**
     * @Route("/new", name="app_about_photos_new", methods={"GET", "POST"})
     */
    public function new(Request $request, AboutPhotosRepository $aboutPhotosRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $aboutPhoto = new AboutPhotos();
        $form = $this->createForm(PhotosType::class, $aboutPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('photo')->getData();

            if ($file) {
                if (!upload($fileUploader->upload($file), $this->getParameter("images_dir"), $aboutPhoto, $aboutPhotosRepository)) {
                    $this->addFlash("danger", "File uploading failed");
                } else {
                    return $this->redirectToRoute('app_about_photos_index', [], Response::HTTP_SEE_OTHER);
                }
            }
        }

        return $this->renderForm('about_photos/new.html.twig', [
            'about_photo' => $aboutPhoto,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'About & About Photos'
        ]);
    }

    /**
     * @Route("/{id}", name="app_about_photos_show", methods={"GET"})
     */
    public function show(AboutPhotos $aboutPhoto, EntityManagerInterface $entityManager): Response
    {
        return $this->render('about_photos/show.html.twig', [
            'about_photo' => $aboutPhoto,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'About & About Photos'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_about_photos_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, AboutPhotos $aboutPhoto, AboutPhotosRepository $aboutPhotosRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(PhotosType::class, $aboutPhoto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var UploadedFile $file */
            $file = $form->get('photo')->getData();

            if ($file) {
                if (!upload($fileUploader->upload($file), $this->getParameter("images_dir"), $aboutPhoto, $aboutPhotosRepository)) {
                    $this->addFlash("danger", "File uploading failed");
                } else {
                    return $this->redirectToRoute('app_about_photos_index', [], Response::HTTP_SEE_OTHER);
                }
            }
        }

        return $this->renderForm('about_photos/edit.html.twig', [
            'about_photo' => $aboutPhoto,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'About & About Photos'
        ]);
    }

    /**
     * @Route("/about/{id}/edit", name="app_about_edit", methods={"GET", "POST"})
     */
    public function aboutEdit(Request $request): Response
    {
        return new Response("");
    }

    /**
     * @Route("/{id}", name="app_about_photos_delete", methods={"POST"})
     */
    public function delete(Request $request, AboutPhotos $aboutPhoto, AboutPhotosRepository $aboutPhotosRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$aboutPhoto->getId(), $request->request->get('_token'))) {
            $aboutPhotosRepository->remove($aboutPhoto, true);
        }

        return $this->redirectToRoute('app_about_photos_index', [], Response::HTTP_SEE_OTHER);
    }
}
