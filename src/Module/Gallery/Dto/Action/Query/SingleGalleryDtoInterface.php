<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Query;

interface SingleGalleryDtoInterface
{
    public const string KEY_ID = 'id';

    public int $id {
        get;
    }
}
