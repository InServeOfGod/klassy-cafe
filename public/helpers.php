<?php

use App\Entity\Contact;
use App\Entity\Notifications;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;

/**
 * @param EntityManagerInterface $entityManager
 * @return array
 */
function notify(EntityManagerInterface $entityManager): array
{
    return $entityManager->getRepository(Notifications::class)->findAll();
}

/**
 * @param EntityManagerInterface $entityManager
 * @return array
 */
function contacts(EntityManagerInterface $entityManager): array
{
    return $entityManager->getRepository(Contact::class)->findAll();
}
