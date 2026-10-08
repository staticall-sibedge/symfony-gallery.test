<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Enum;

use App\Module\Gallery\Enum\GalleryStatusEnum as TestableEnum;
use PHPUnit\Framework\TestCase;

final class GalleryStatusEnumTest extends TestCase
{
    public function testValues(): void
    {
        $cases = TestableEnum::cases();
        $expected = [];

        foreach ($cases as $case) {
            $expected[] = $case->value;
        }

        self::assertSame($expected, TestableEnum::values());
    }
}
