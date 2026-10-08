<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Store;

interface UploadImageToGalleryDtoInterface
{
    public const string KEY_FILE_ID = 'fileId';
    public const string KEY_SORT_ORDER = 'sortOrder';

    public string $fileId {
        get;
    }

    public ?int $sortOrder {
        get;
    }
}
