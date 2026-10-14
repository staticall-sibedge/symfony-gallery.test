<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Store;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Store\CreateGalleryDtoInterface;
use App\Module\Gallery\Enum\GalleryStatusEnum;
use Doctrine\ORM\EntityManagerInterface;

final class CreateGalleryAction implements CreateGalleryActionInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function execute(CreateGalleryDtoInterface $actionDto): Gallery
    {
        $gallery = new Gallery();

        $gallery->setName($actionDto->name);
        $gallery->setDescription($actionDto->description);
        $gallery->setStatus(GalleryStatusEnum::from($actionDto->status));

        $this->entityManager->persist($gallery);
        $this->entityManager->flush();

        return $gallery;
    }
}
