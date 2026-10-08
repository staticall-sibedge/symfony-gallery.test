<?php declare(strict_types=1);

namespace App\Module\Gallery\Action\Query;

use App\Module\Gallery\Dto\Action\Query\ListGalleriesFilterDtoInterface;
use Doctrine\Common\Collections\Collection;

interface ListGalleriesActionInterface
{
    public function execute(ListGalleriesFilterDtoInterface $actionDto): Collection;
}
