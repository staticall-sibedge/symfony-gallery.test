<?php declare(strict_types=1);

namespace App\Repository;

use Doctrine\ORM\QueryBuilder;

interface RepositoryInterface
{
    public function getInstancedQueryBuilder(): QueryBuilder;
}
