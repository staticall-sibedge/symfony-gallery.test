<?php declare(strict_types=1);

namespace App\Module\File\Dto\Action\Store;

use App\Module\Dto\Action\ActionDtoInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class UploadFileDto implements UploadFileDtoInterface, ActionDtoInterface
{
    public const string REQUEST_KEY_FILE = 'file';
    public const int|float VALIDATION_MAX_WIDTH = 3000;
    public const int|float VALIDATION_MAX_HEIGHT = 3000;

    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Image(maxWidth: self::VALIDATION_MAX_WIDTH, maxHeight: self::VALIDATION_MAX_HEIGHT)]
        public readonly UploadedFile $uploadedFile,
    )
    {
    }

    public function toArray(): array
    {
        return [
            self::KEY_UPLOADED_FILE => $this->uploadedFile,
        ];
    }

    public static function fromRequest(Request $request, string $fileKey = self::REQUEST_KEY_FILE): static
    {
        return new static(
            $request->files->get($fileKey),
        );
    }
}
