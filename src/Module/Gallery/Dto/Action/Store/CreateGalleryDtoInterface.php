<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Store;

use App\Module\Gallery\Enum\GalleryStatusEnum;

interface CreateGalleryDtoInterface
{
    public const string KEY_NAME = 'name';
    public const string KEY_DESCRIPTION = 'description';
    public const string KEY_STATUS = 'status';

    public string $name {
        get;
    }

    public ?string $description {
        get;
    }

    public string|GalleryStatusEnum $status {
        get;
    }
}
