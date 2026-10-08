<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Query;

use App\Module\Gallery\Enum\GalleryStatusEnum;

interface ListGalleriesFilterDtoInterface
{
    public const string KEY_IS_PINNED = 'isPinned';
    public const string KEY_STATUS = 'status';

    public ?bool $isPinned {
        get;
    }

    public null|string|GalleryStatusEnum $status {
        get;
    }
}
