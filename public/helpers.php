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

/**
 * @param string|null $filename
 * @param string $images_dir
 * @param object $entity
 * @param ObjectRepository $repository
 * @return bool
 */
function upload(?string $filename, string $images_dir, object $entity, ObjectRepository $repository): bool
{
    if ($filename !== null) {
        $filename = $images_dir . $filename;
        $entity->setPhoto($filename);
        $repository->add($entity, true);
        return true;
    }

    return false;
}
