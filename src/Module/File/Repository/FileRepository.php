<?php declare(strict_types=1);

namespace App\Module\File\Repository;

use App\Entity\File;
use App\Module\File\Enum\FileStatusEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Uid\Uuid;

/**
 * @extends ServiceEntityRepository<File>
 */
class FileRepository extends ServiceEntityRepository implements FileRepositoryInterface
{
    public const string PREFIX = 'f';

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, File::class);
    }

    public function getInstancedQueryBuilder(): QueryBuilder
    {
        return $this->createQueryBuilder(self::PREFIX);
    }

    public function findOneById(string|Uuid $id, ?FileStatusEnum $statusEnum = null): File
    {
        $qb = $this->getInstancedQueryBuilder()->where('id = :id')->setParameter('id', $id);

        return $qb->getQuery()->getSingleResult();
    }
}
