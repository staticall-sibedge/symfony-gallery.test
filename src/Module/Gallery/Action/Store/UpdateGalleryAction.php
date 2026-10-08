<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Store;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Store\UpdateGalleryDtoInterface;
use Doctrine\ORM\EntityManagerInterface;

final class UpdateGalleryAction implements UpdateGalleryActionInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function execute(Gallery $gallery, UpdateGalleryDtoInterface $actionDto): Gallery
    {
        $gallery->setName($actionDto->name);
        $gallery->setDescription($actionDto->description);
        $gallery->setStatus($actionDto->status);

        $this->entityManager->flush();

        return $gallery;
    }
}
