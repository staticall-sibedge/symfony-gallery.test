<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Store;

interface AppendFileToGalleryDtoInterface
{
    public const string KEY_SORT_ORDER = 'sortOrder';

    public ?int $sortOrder {
        get;
    }
}
