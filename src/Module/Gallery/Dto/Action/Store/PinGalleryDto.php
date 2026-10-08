<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Store;

use App\Module\Dto\Action\ActionDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class PinGalleryDto implements PinGalleryDtoInterface, ActionDtoInterface
{
    public function __construct(
        #[Assert\NotBlank()]
        public readonly bool $isPinned,
    )
    {
    }

    public function toArray(): array
    {
        return [
            self::KEY_IS_PINNED => $this->isPinned,
        ];
    }

    public static function fromRequest(Request $request): static
    {
        return new static((bool) $request->request->get(self::KEY_IS_PINNED));
    }
}
