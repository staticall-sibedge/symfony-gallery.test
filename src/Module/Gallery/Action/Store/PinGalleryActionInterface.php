<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Store;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Store\PinGalleryDtoInterface;

interface PinGalleryActionInterface
{
    public function execute(Gallery $gallery, PinGalleryDtoInterface $actionDto): Gallery;
}
