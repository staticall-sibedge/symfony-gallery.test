<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Query;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Query\SingleGalleryDtoInterface;

interface SingleGalleryActionInterface
{
    public function execute(SingleGalleryDtoInterface $actionDto): Gallery;
}
