<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Store;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Store\PinGalleryDtoInterface;
use Doctrine\ORM\EntityManagerInterface;
use Safe\DateTimeImmutable;

final class PinGalleryAction implements PinGalleryActionInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function execute(Gallery $gallery, PinGalleryDtoInterface $actionDto): Gallery
    {
        $gallery->setIsPinned($actionDto->isPinned);
        $gallery->setPinnedAt(new DateTimeImmutable());

        $this->entityManager->flush();

        return $gallery;
    }
}
