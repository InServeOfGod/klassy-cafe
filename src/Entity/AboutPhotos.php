<?php

namespace App\Entity;

use App\Repository\AboutPhotosRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=AboutPhotosRepository::class)
 */
class AboutPhotos
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Assert\Regex (
     *     pattern="/\d/"
     * )
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\File(
     *     maxSize="2048k",
     *     mimeTypes={"image/png", "image/jpg", "image/jpeg"}
     * )
     * @Assert\Length(
     *     max=255,
     *     maxMessage="len.photo"
     * )
     */
    private $photo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }
}
