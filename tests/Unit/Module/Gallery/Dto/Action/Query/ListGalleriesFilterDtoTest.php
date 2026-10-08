<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Dto\Action\Query;

use App\Module\Gallery\Dto\Action\Query\ListGalleriesFilterDto as TestableDto;
use App\Module\Gallery\Enum\GalleryStatusEnum;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class ListGalleriesFilterDtoTest extends TestCase
{
    public function testEmptyRequest(): void
    {
        $request = Request::createFromGlobals();

        $emptyDto = new TestableDto();
        $dtoFromFactory = TestableDto::fromRequest($request);

        self::assertSame($emptyDto->toArray(), $dtoFromFactory->toArray());
    }

    public function testWithStatusFilter(): void
    {
        $request = Request::createFromGlobals();
        $request->initialize(
            [
                TestableDto::KEY_STATUS => GalleryStatusEnum::DRAFT->value,
            ],
        );

        $dtoFromFactory = TestableDto::fromRequest($request);

        self::assertNull($dtoFromFactory->toArray()[TestableDto::KEY_IS_PINNED]);
        self::assertSame(GalleryStatusEnum::DRAFT->value, $dtoFromFactory->toArray()[TestableDto::KEY_STATUS]);
    }

    public function testWithIsPinnedFilter(): void
    {
        $request = Request::createFromGlobals();
        $request->initialize(
            [
                TestableDto::KEY_IS_PINNED => true,
            ],
        );

        $dtoFromFactory = TestableDto::fromRequest($request);

        self::assertNull($dtoFromFactory->toArray()[TestableDto::KEY_STATUS]);
        self::assertSame(true, $dtoFromFactory->toArray()[TestableDto::KEY_IS_PINNED]);
    }
}
