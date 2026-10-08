<?php declare(strict_types=1);

namespace App\Module\File\Action\Store;

use App\Entity\File;
use App\Module\File\Dto\Action\Store\UploadFileDtoInterface;

interface UploadFileActionInterface
{
    public function execute(UploadFileDtoInterface $actionDto): File;
}
