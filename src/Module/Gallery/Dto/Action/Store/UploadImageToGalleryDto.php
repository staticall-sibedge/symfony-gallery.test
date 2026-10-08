<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Store;

use App\Module\Dto\Action\ActionDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class UploadImageToGalleryDto implements UploadImageToGalleryDtoInterface, ActionDtoInterface
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Uuid()]
        public readonly string $fileId,

        #[Assert\Length(min: 1, max: 512)]
        public readonly ?int $sortOrder,
    )
    {
    }

    public function toArray(): array
    {
        return [
            self::KEY_FILE_ID => $this->fileId,
            self::KEY_SORT_ORDER => $this->sortOrder,
        ];
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            (string) $request->request->get(self::KEY_FILE_ID),
            $request->request->has(self::KEY_SORT_ORDER) ? (string) $request->request->get(self::KEY_SORT_ORDER) : null,
        );
    }
}
