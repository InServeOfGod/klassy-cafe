<?php

namespace App\Controller;

use App\Entity\Customers;
use App\Entity\Emails;
use App\Entity\Guests;
use App\Entity\OffersMenus;
use App\Entity\PhoneNumbers;
use App\Entity\Profits;
use App\Form\GuestsCountType;
use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
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
        $product_count = 0;

        $offersMenusRepo = $entityManager->getRepository(OffersMenus::class);
        $profitRepo = $entityManager->getRepository(Profits::class);
        $customerRepo = $entityManager->getRepository(Customers::class);
        $emailsRepo = $entityManager->getRepository(Emails::class);
        $phoneRepo = $entityManager->getRepository(PhoneNumbers::class);
        $guestsRepo = $entityManager->getRepository(Guests::class);

        $offers_menus = $offersMenusRepo->findAll();
        $profits = $profitRepo->findAll();
        $customers = $customerRepo->findAll();
        $emails = $emailsRepo->findAll();
        $phones = $phoneRepo->findAll();
        $guests = $guestsRepo->findBy([], ['guest' => 'asc']);
        $guest_count = count($guests);

        // setup guests count form field

        $form = $this->createForm(GuestsCountType::class, null, [
            'data' => [
                'guest_count' => $guest_count
            ]
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $guest_count = (int)$form->getData()['guest_count'];

            for ($i = 1; $i <= $guest_count; $i++) {
                try {
                    $guestsRepo->remove($guests[$i-1], true);
                } catch (ForeignKeyConstraintViolationException $violationException) {
                    $this->addFlash("info", "You can not decrease the count of guests due to reservations made!");
                    dump($violationException);
                    break;
                }

                $guestEntity = new Guests();
                $guestEntity->setGuest($i);
                $entityManager->persist($guestEntity);
                $entityManager->flush();
            }

            return $this->redirectToRoute("app_settings");
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
}
