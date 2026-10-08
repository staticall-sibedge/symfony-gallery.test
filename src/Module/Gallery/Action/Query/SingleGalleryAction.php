<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Query;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Query\SingleGalleryDtoInterface;
use App\Module\Gallery\Repository\GalleryRepositoryInterface;

final class SingleGalleryAction implements SingleGalleryActionInterface
{
    public function __construct(private readonly GalleryRepositoryInterface $repository)
    {
    }

    public function execute(SingleGalleryDtoInterface $actionDto): Gallery
    {
        return $this->repository->findOneById($actionDto->id);
    }
}
