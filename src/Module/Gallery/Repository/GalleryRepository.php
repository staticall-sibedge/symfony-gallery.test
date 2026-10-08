<?php declare(strict_types=1);

namespace App\Module\Gallery\Repository;

use App\Entity\Gallery;
use App\Module\Gallery\Dto\Action\Query\ListGalleriesFilterDtoInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Gallery>
 */
class GalleryRepository extends ServiceEntityRepository implements GalleryRepositoryInterface
{
    public const string PREFIX = 'g';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Gallery::class);
    }

    public function getInstancedQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder(self::PREFIX);
    }

    /**
     * @return Collection<int, Gallery>
     */
    public function listGalleriesByFilter(ListGalleriesFilterDtoInterface $dto): Collection
    {
        $p = self::PREFIX;

        $qb = $this->getInstancedQueryBuilder()
            ->orderBy("{$p}.id", 'ASC')
            ->setMaxResults(10)
        ;

        if ($dto->isPinned !== null) {
            $qb
                ->andWhere("{$p}.isPinned = :isPinned")
                ->setParameter('isPinned', $dto->isPinned)
            ;
        }

        if ($dto->status !== null) {
            $qb
                ->andWhere("{$p}.status = :status")
                ->setParameter('status', $dto->status)
            ;
        }

        return $qb
            ->getQuery()
            ->getResult()
        ;
    }

    public function findOneById(int $id): Gallery
    {
        $qb = $this->getInstancedQueryBuilder()->where('id = :id')->setParameter('id', $id);

        return $qb->getQuery()->getSingleResult();
    }
}
