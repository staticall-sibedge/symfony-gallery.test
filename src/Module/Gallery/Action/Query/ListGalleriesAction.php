<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Query;

use App\Module\Gallery\Dto\Action\Query\ListGalleriesFilterDtoInterface;
use App\Module\Gallery\Repository\GalleryRepositoryInterface;
use Doctrine\Common\Collections\Collection;

final class ListGalleriesAction implements ListGalleriesActionInterface
{
    public function __construct(private readonly GalleryRepositoryInterface $repository)
    {
    }

    public function execute(ListGalleriesFilterDtoInterface $actionDto): Collection
    {
        return $this->repository->listGalleriesByFilter($actionDto);
    }
}
