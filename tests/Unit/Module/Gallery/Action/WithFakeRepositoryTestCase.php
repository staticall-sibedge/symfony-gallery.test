<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Action;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Query\ListGalleriesFilterDtoInterface;
use App\Module\Gallery\Repository\GalleryRepositoryInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver\SQLite3\Driver;
use Doctrine\ORM\Configuration;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;

abstract class WithFakeRepositoryTestCase extends TestCase
{
    protected function getRepositoryMock(Collection $galleriesCollection): GalleryRepositoryInterface
    {
        $repository = new class implements GalleryRepositoryInterface {
            private ?Collection $items = null;

            public function setItems(Collection $collection)
            {
                $this->items = $collection;
            }

            public function listGalleriesByFilter(ListGalleriesFilterDtoInterface $dto): Collection
            {
                $collection = $this->items;

                if ($dto->isPinned !== null) {
                    $collection = $collection->filter(static fn(Gallery $gallery) => $gallery->isPinned() === $dto->isPinned);
                }

                if ($dto->status !== null) {
                    $collection = $collection->filter(static fn(Gallery $gallery) => $gallery->getStatus()->value === (is_object($dto->status) ? $dto->status->value : $dto->status));
                }

                return $collection;
            }

            public function findOneById(int $id): Gallery
            {
                foreach ($this->items as $gallery) {
                    if ($gallery->getId() === $id) {
                        return $gallery;
                    }
                }

                throw new NoResultException();
            }

            public function getInstancedQueryBuilder(): QueryBuilder
            {
                return new QueryBuilder(new EntityManager(new Connection([], new Driver()), new Configuration()));
            }
        };

        $repository->setItems($galleriesCollection);

        return $repository;
    }
}
