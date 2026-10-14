<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Store;

use App\Entity\File;
use App\Entity\Gallery;
use App\Entity\GalleryImage;
use App\Module\Gallery\Dto\Action\Store\AppendFileToGalleryDtoInterface;
use Doctrine\ORM\EntityManagerInterface;

final class AppendFileToGalleryAction implements AppendFileToGalleryActionInterface
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    public function execute(Gallery $gallery, File $file, AppendFileToGalleryDtoInterface $actionDto): GalleryImage
    {
        if ($gallery->hasByFile($file) === true) {
            return $gallery->getByFile($file);
        }

        $galleryImage = new GalleryImage();

        $galleryImage->setSortOrder($actionDto->sortOrder);
        $galleryImage->setGallery($gallery);
        $galleryImage->setFile($file);

        $gallery->getImages()->add($galleryImage);

        $this->entityManager->persist($galleryImage);
        $this->entityManager->flush();

        return $galleryImage;
    }
}
