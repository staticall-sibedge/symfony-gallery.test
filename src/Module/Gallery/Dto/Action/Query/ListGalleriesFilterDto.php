<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Query;

use App\Module\Dto\Action\ActionDtoInterface;
use App\Module\Gallery\Enum\GalleryStatusEnum;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class ListGalleriesFilterDto implements ListGalleriesFilterDtoInterface, ActionDtoInterface
{
    public function __construct(
        #[Assert\Choice(choices: [true, false])]
        public readonly ?bool $isPinned = null,
        #[Assert\Choice(callback: [GalleryStatusEnum::class, 'values'])]
        public readonly ?string $status = null,
    )
    {
    }

    public function toArray(): array
    {
        return [
            self::KEY_IS_PINNED => $this->isPinned,
            self::KEY_STATUS => $this->status,
        ];
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            $request->query->has(self::KEY_IS_PINNED) ? (bool) $request->query->get(self::KEY_IS_PINNED) : null,
            $request->query->has(self::KEY_STATUS) ? $request->query->get(self::KEY_STATUS) : null,
        );
    }
}
