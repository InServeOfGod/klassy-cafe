<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("admin/user")
 * @IsGranted("ROLE_ADMIN")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user, EntityManagerInterface $entityManager): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'User'
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository, EntityManagerInterface $entityManager, FileUploader $fileUploader, UserPasswordHasherInterface $passwordHasher): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $plainTextPassword = $form->getData()->getPassword();

            // hash provided password if not provided then use the old one.
            if ($plainTextPassword == null) {
                $user->setPassword($user->getPassword());
            } else {
                $hashedPassword = $passwordHasher->hashPassword(
                    $user,
                    $plainTextPassword
                );

                $user->setPassword($hashedPassword);
            }


            $userRepository->add($user, true);
            return $this->redirectToRoute('app_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'contacts' => contacts($entityManager),
            'notifications' => notify($entityManager),
            'title' => 'User'
        ]);
    }
}
