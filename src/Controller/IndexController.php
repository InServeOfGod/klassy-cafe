<?php

namespace App\Controller;

use App\Entity\About;
use App\Entity\AboutPhotos;
use App\Entity\Chefs;
use App\Entity\ChefsSlider;
use App\Entity\Contact;
use App\Entity\ContactUs;
use App\Entity\DayTimes;
use App\Entity\Emails;
use App\Entity\HeaderImages;
use App\Entity\HeaderSlider;
use App\Entity\Menu;
use App\Entity\MenuSlider;
use App\Entity\Offers;
use App\Entity\OffersMenus;
use App\Entity\PhoneNumbers;
use App\Entity\Settings;
use App\Form\ContactType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        $offers = [];

        $settingsRepo = $entityManager->getRepository(Settings::class);
        $headerImagesRepo = $entityManager->getRepository(HeaderImages::class);
        $headerSlidesRepo = $entityManager->getRepository(HeaderSlider::class);
        $aboutRepo = $entityManager->getRepository(About::class);
        $aboutPhotosRepo = $entityManager->getRepository(AboutPhotos::class);
        $menuRepo = $entityManager->getRepository(Menu::class);
        $menuSlidersRepo = $entityManager->getRepository(MenuSlider::class);
        $chefRepo = $entityManager->getRepository(Chefs::class);
        $chefSliderRepo = $entityManager->getRepository(ChefsSlider::class);
        $contactUsRepo = $entityManager->getRepository(ContactUs::class);
        $phoneNumbersRepo = $entityManager->getRepository(PhoneNumbers::class);
        $emailsRepo = $entityManager->getRepository(Emails::class);
        $offerRepo = $entityManager->getRepository(Offers::class);
        $offersMenuRepo = $entityManager->getRepository(OffersMenus::class);
        $dayTimeRepo = $entityManager->getRepository(DayTimes::class);

        // we do not need all data so just get first index of this one
        $setting = $settingsRepo->findAll()[0];
        $headerImage = $headerImagesRepo->findAll()[0];
        $about = $aboutRepo->findAll()[0];
        $menu = $menuRepo->findAll()[0];
        $chef = $chefRepo->findAll()[0];
        $contactUs = $contactUsRepo->findAll()[0];
        $offer = $offerRepo->findAll()[0];

        $headerSlides = $headerSlidesRepo->findAll();
        $aboutPhotos = $aboutPhotosRepo->findAll();
        $menuSliders = $menuSlidersRepo->findAll();
        $chefsSliders = $chefSliderRepo->findAll();
        $phoneNumbers = $phoneNumbersRepo->findAll();
        $emails = $emailsRepo->findAll();
        $dayTime = $dayTimeRepo->findAll();

        foreach ($dayTime as $item) {
            $offers[] = $offersMenuRepo->findBy([
                'day_time' => $item->getId()
            ]);
        }

        // testing area
//        dump($offers);

        // make contact form
        $contactEntity = new Contact();
        $contactForm = $this->createForm(ContactType::class, $contactEntity);
        $contactForm->handleRequest($request);

        if ($contactForm->isSubmitted() and $contactForm->isValid()) {
            $contactEntity->setDateContact(new DateTime("now"));
            $entityManager->persist($contactEntity);
            $entityManager->flush();
        }

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
            'menu_sliders' => $menuSliders,
            'chef_title' => $chef->getTitle(),
            'chefs' => $chefsSliders,
            'contact_us' => $contactUs,
            'phone_numbers' => $phoneNumbers,
            'emails' => $emails,
            'contactForm' => $contactForm->createView(),
            'offer_title' => $offer->getTitle(),
            'dayTimes' => $dayTime,
            'offers' => $offers,
        ]);
    }
}
