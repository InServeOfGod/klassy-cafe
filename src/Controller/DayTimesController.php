<?php

namespace App\Controller;

use App\Entity\DayTimes;
use App\Form\DayTimesType;
use App\Repository\DayTimesRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/day-times")
 * @IsGranted("ROLE_ADMIN")
 */
class DayTimesController extends AbstractController
{
    /**
     * @Route("/", name="app_day_times_index", methods={"GET"})
     */
    public function index(DayTimesRepository $dayTimesRepository, EntityManagerInterface $entityManager): Response
    {
        return $this->render('day_times/index.html.twig', [
            'day_times' => $dayTimesRepository->findAll(),
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Day Times',
        ]);
    }

    /**
     * @Route("/new", name="app_day_times_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DayTimesRepository $dayTimesRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $dayTime = new DayTimes();
        $form = $this->createForm(DayTimesType::class, $dayTime);
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
                    $dayTime->setPhoto($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $dayTimesRepository->add($dayTime, true);
            return $this->redirectToRoute('app_day_times_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('day_times/new.html.twig', [
            'day_time' => $dayTime,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Day Times',
        ]);
    }

    /**
     * @Route("/{id}", name="app_day_times_show", methods={"GET"})
     */
    public function show(DayTimes $dayTime, EntityManagerInterface $entityManager): Response
    {
        return $this->render('day_times/show.html.twig', [
            'day_time' => $dayTime,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Day Times',
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_day_times_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, DayTimes $dayTime, DayTimesRepository $dayTimesRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(DayTimesType::class, $dayTime);
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
                    $dayTime->setPhoto($filename);
                } else {
                    $this->addFlash("danger", "File uploading failed");
                }
            }

            $dayTimesRepository->add($dayTime, true);
            return $this->redirectToRoute('app_day_times_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('day_times/edit.html.twig', [
            'day_time' => $dayTime,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'Day Times',
        ]);
    }

    /**
     * @Route("/{id}", name="app_day_times_delete", methods={"POST"})
     */
    public function delete(Request $request, DayTimes $dayTime, DayTimesRepository $dayTimesRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$dayTime->getId(), $request->request->get('_token'))) {
            try {
                $dayTimesRepository->remove($dayTime, true);
                unlink($this->getParameter('public_dir').$dayTime->getPhoto());

            } catch (Exception $exception) {
                $this->addFlash("warning", "You are trying to delete a time of the day which is bound to offers menus! Please try to delete referred menus if you want to.");
            }
        }

        return $this->redirectToRoute('app_day_times_index', [], Response::HTTP_SEE_OTHER);
    }
}
