<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Entity\Emails;
use App\Entity\Guests;
use App\Entity\OffersMenus;
use App\Entity\PhoneNumbers;
use App\Entity\Profits;
use App\Entity\User;
use App\Form\GuestsCountType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin/settings")
 * @IsGranted("ROLE_ADMIN")
 */
class SettingsController extends AbstractController
{
    /**
     * @Route("/", name="app_settings")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product_count = $guest_count = 0;

        $offersMenusRepo = $entityManager->getRepository(OffersMenus::class);
        $profitRepo = $entityManager->getRepository(Profits::class);
        $customerRepo = $entityManager->getRepository(Customers::class);
        $emailsRepo = $entityManager->getRepository(Emails::class);
        $phoneRepo = $entityManager->getRepository(PhoneNumbers::class);
        $guestsRepo = $entityManager->getRepository(Guests::class);

        $offers_menus = $offersMenusRepo->findAll();
        $profits = $profitRepo->findAll();
        $customers = $customerRepo->findAllWithMenus();
        $emails = $emailsRepo->findAll();
        $phones = $phoneRepo->findAll();
        $guest_count = $guestsRepo->findOneBy([], ['id' => 'desc'])->getGuest();

        // setup guests count form field

        $form = $this->createForm(GuestsCountType::class, null, [
            'data' => [
                'guest_count' => $guest_count
            ]
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

        }

        // get product count
        foreach ($offers_menus as $offers_menu) {
            $product_count += $offers_menu->getCount();
        }

        return $this->render('admin_settings/index.html.twig', [
                'title' => 'Settings',
                'product_count' => $product_count,
                'customers' => $customers,
                'profits' => $profits,
                'contacts' => contacts($entityManager),
                'notifications' => notify($entityManager),
                'emails' => $emails,
                'phones' => $phones,
                'guest_count' => $guest_count,
                'form' => $form->createView()
            ]
        );
    }

    /**
//     * @Route("/user/{id}/edit", name="app_user_edit", methods={"GET", "POST"})
     */
    /*public function editUser(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // if user only wants to edit username then get the current password from database without changing
            if ($user->getPassword() === null) {
                $user->setPassword($this->getUser()->getPassword());
            }

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_admin_settings', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin_settings/edit.html.twig', [
            'user' => $user,
            'form' => $form,
            'title' => 'Edit User',
            'contacts' => $this->contacts,
            'notifications' => $this->notifications,
        ]);
    }*/
}
