<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Store;

use App\Entity\File;
use App\Entity\Gallery;
use App\Entity\GalleryImage;
use App\Module\Gallery\Dto\Action\Store\AppendFileToGalleryDtoInterface;

interface AppendFileToGalleryActionInterface
{
    public function execute(Gallery $gallery, File $file, AppendFileToGalleryDtoInterface $actionDto): GalleryImage;
}
