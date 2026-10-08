<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Store;

use App\Module\Dto\Action\ActionDtoInterface;
use App\Module\Gallery\Enum\GalleryStatusEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateGalleryDto implements CreateGalleryDtoInterface, ActionDtoInterface
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\Length(min: 1, max: 64)]
        public readonly string $name,

        #[Assert\Length(min: 1, max: 512)]
        public readonly ?string $description,

        #[Assert\NotBlank()]
        #[Assert\Choice(callback: [GalleryStatusEnum::class, 'values'])]
        public readonly string|GalleryStatusEnum $status,
    )
    {
    }

    public function toArray(): array
    {
        return [
            self::KEY_NAME => $this->name,
            self::KEY_DESCRIPTION => $this->description,
            self::KEY_STATUS => GalleryStatusEnum::from($this->status),
        ];
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            (string) $request->request->get(self::KEY_NAME),
            $request->request->get(self::KEY_DESCRIPTION),
            (string) $request->request->get(self::KEY_STATUS),
        );
    }
}
