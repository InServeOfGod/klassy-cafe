<?php

namespace App\Controller;

use App\Entity\Coffer;
use App\Entity\Customers;
use App\Entity\OffersMenus;
use App\Entity\Profits;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

/**
 * @Route("/admin")
 * @IsGranted("ROLE_ADMIN")
 */
class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="app_dashboard")
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $product_count = $earn = 0;

        $offersMenusRepo = $entityManager->getRepository(OffersMenus::class);
        $profitRepo = $entityManager->getRepository(Profits::class);
        $customerRepo = $entityManager->getRepository(Customers::class);
        $cofferRepo = $entityManager->getRepository(Coffer::class);

        $offers_menus = $offersMenusRepo->findAll();
        $profits = $profitRepo->findAll();
        $customers = $customerRepo->findAll();
        $money = $cofferRepo->findOneBy([])->getMoney();

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
                'contacts' => contacts($entityManager),
                'notifications' => notify($entityManager),
            ]
        );
    }
}
