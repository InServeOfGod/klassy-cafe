<?php

namespace App\Controller;

use App\Entity\About;
use App\Entity\AboutPhotos;
use App\Entity\HeaderImages;
use App\Entity\HeaderSlider;
use App\Entity\Menu;
use App\Entity\MenuSlider;
use App\Entity\Settings;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $settingsRepo = $entityManager->getRepository(Settings::class);
        $headerImagesRepo = $entityManager->getRepository(HeaderImages::class);
        $headerSlidesRepo = $entityManager->getRepository(HeaderSlider::class);
        $aboutRepo = $entityManager->getRepository(About::class);
        $aboutPhotosRepo = $entityManager->getRepository(AboutPhotos::class);
        $menuRepo = $entityManager->getRepository(Menu::class);
        $menuSlidersRepo = $entityManager->getRepository(MenuSlider::class);

        // we do not need all data so just get first index of this one
        $setting = $settingsRepo->findAll()[0];
        $headerImage = $headerImagesRepo->findAll()[0];
        $about = $aboutRepo->findAll()[0];
        $menu = $menuRepo->findAll()[0];

        $headerSlides = $headerSlidesRepo->findAll();
        $aboutPhotos = $aboutPhotosRepo->findAll();
        $menuSliders = $menuSlidersRepo->findAll();

        return $this->render('index/index.html.twig', [
            'header_logo' => $setting->getHeaderLogo(),
            'footer_logo' => $setting->getFooterLogo(),
            'facebook' => $setting->getFacebook(),
            'twitter' => $setting->getTwitter(),
            'linkedin' => $setting->getLinkedin(),
            'instagram' => $setting->getInstagram(),
            'copyright' => $setting->getCopyright(),
            'image_title' => $headerImage->getTitle(),
            'image_subtitle' => $headerImage->getSubtitle(),
            'image_button' => $headerImage->getButton(),
            'image_bg' => $headerImage->getBg(),
            'header_slides' => $headerSlides,
            'about_title' => $about->getTitle(),
            'about_video_link' => $about->getVideoLink(),
            'about_video_bg' => $about->getVideoBg(),
            'about_photos' => $aboutPhotos,
            'menu_title' => $menu->getTitle(),
            'menu_sliders' => $menuSliders
        ]);
    }
}
