<?php declare(strict_types=1);

namespace App\Module\File\Message;

use Ramsey\Uuid\Uuid;

final class FileUploadedMessage
{
    public function __construct(public readonly string|Uuid $fileId)
    {
    }
}
