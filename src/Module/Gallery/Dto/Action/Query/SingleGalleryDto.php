<?php declare(strict_types=1);

namespace App\Module\Gallery\Dto\Action\Query;

use App\Module\Dto\Action\ActionDtoInterface;
use App\Module\Gallery\Dto\Action\Query\SingleGalleryDto\RequiredDataIsMissingException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;

final class SingleGalleryDto implements SingleGalleryDtoInterface, ActionDtoInterface
{
    public function __construct(
        #[Assert\NotBlank()]
        #[Assert\GreaterThan(0)]
        public readonly int $id,
    )
    {
    }

    public function toArray(): array
    {
        return [
            self::KEY_ID => $this->id,
        ];
    }

    public static function fromRequest(Request $request): static
    {
        if ($request->request->has(self::KEY_ID) === false) {
            throw new RequiredDataIsMissingException('You must provide an ID to use this DTO');
        }

        return new static((int) $request->request->get(self::KEY_ID));
    }
}
