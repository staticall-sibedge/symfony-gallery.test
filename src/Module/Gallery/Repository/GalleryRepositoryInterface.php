<?php declare(strict_types=1);

namespace App\Module\Gallery\Repository;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Query\ListGalleriesFilterDtoInterface;
use App\Repository\RepositoryInterface;
use Doctrine\Common\Collections\Collection;

interface GalleryRepositoryInterface extends RepositoryInterface
{
    public function listGalleriesByFilter(ListGalleriesFilterDtoInterface $dto): Collection;
    public function findOneById(int $id): Gallery;
}
