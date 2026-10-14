<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Store;

use App\Module\Dto\Action\ActionDtoInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class AppendFileToGalleryDto implements AppendFileToGalleryDtoInterface, ActionDtoInterface
{
    public function __construct(
        #[Assert\Length(min: 1, max: 512)]
        public readonly ?int $sortOrder,
    )
    {
    }

    public function toArray(): array
    {
        return [
            self::KEY_SORT_ORDER => $this->sortOrder,
        ];
    }

    public static function fromRequest(Request $request): static
    {
        return new static(
            $request->request->has(self::KEY_SORT_ORDER) ? (string) $request->request->get(self::KEY_SORT_ORDER) : null,
        );
    }
}
