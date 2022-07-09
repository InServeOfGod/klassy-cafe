<?php

namespace App\Controller;

use App\Entity\Coffer;
use App\Entity\Contact;
use App\Entity\Customers;
use App\Entity\Notifications;
use App\Entity\OffersMenus;
use App\Entity\Profits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin_index")
     */
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        if (!$this->isGranted("ROLE_ADMIN")) {
            return $this->redirectToRoute("app_admin_login");
        }

        $product_count = $earn = 0;

        $offersMenusRepo = $entityManager->getRepository(OffersMenus::class);
        $profitRepo = $entityManager->getRepository(Profits::class);
        $customerRepo = $entityManager->getRepository(Customers::class);
        $cofferRepo = $entityManager->getRepository(Coffer::class);
        $contactRepo = $entityManager->getRepository(Contact::class);
        $notificationsRepo = $entityManager->getRepository(Notifications::class);

        $offers_menus = $offersMenusRepo->findAll();
        $profits = $profitRepo->findAll();
        $customers = $customerRepo->findAllWithMenus();
        $money = $cofferRepo->findOneBy([])->getMoney();
        $contacts = $contactRepo->findAll();
        $notifications = $notificationsRepo->findAll();

        // get product count
        foreach ($offers_menus as $offers_menu) {
            $product_count += $offers_menu->getCount();
        }

        // calculate all profits
        foreach ($profits as $profit) {
            $earn += $profit->getProfit();
            $earn -= $profit->getLoss();
        }

        return $this->render('admin/index.html.twig', [
                'title' => 'Dashboard',
                'product_count' => $product_count,
                'customers' => $customers,
                'profits' => $profits,
                'money' => $money,
                'profit' => $earn,
                'contacts' => $contacts,
                'notifications' => $notifications,
            ]
        );
    }
}
