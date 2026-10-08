<?php declare(strict_types=1);

namespace App\Module\File\Repository;

use App\Entity\File;
use App\Module\File\Enum\FileStatusEnum;
use App\Repository\RepositoryInterface;
use Symfony\Component\Uid\Uuid;

interface FileRepositoryInterface extends RepositoryInterface
{
    public function findOneById(string|Uuid $id, ?FileStatusEnum $statusEnum = null): File;
}
