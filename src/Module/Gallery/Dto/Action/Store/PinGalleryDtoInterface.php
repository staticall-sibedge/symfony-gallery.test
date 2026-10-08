<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Store;

interface PinGalleryDtoInterface
{
    public const string KEY_IS_PINNED = 'isPinned';

    public bool $isPinned {
        get;
    }
}
