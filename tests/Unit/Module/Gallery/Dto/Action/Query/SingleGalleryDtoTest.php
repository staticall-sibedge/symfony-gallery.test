<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Dto\Action\Query;

use App\Module\Gallery\Dto\Action\Query\SingleGalleryDto as TestableDto;
use App\Module\Gallery\Dto\Action\Query\SingleGalleryDto\RequiredDataIsMissingException;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

final class SingleGalleryDtoTest extends TestCase
{
    public function testEmptyRequest(): void
    {
        $request = Request::createFromGlobals();

        $this->expectException(RequiredDataIsMissingException::class);

        TestableDto::fromRequest($request);
    }

    public function testRequestWithValue(): void
    {
        $request = Request::createFromGlobals();
        $request->initialize([], [TestableDto::KEY_ID => 5]);

        $dto = TestableDto::fromRequest($request);

        self::assertSame([TestableDto::KEY_ID => 5], $dto->toArray());
    }
}
