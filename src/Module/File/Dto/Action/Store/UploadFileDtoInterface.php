<?php declare(strict_types=1);

namespace App\Module\File\Dto\Action\Store;

use Symfony\Component\HttpFoundation\File\UploadedFile;

interface UploadFileDtoInterface
{
    public const string KEY_UPLOADED_FILE = 'uploadedFile';

    public UploadedFile $uploadedFile {
        get;
    }
}
