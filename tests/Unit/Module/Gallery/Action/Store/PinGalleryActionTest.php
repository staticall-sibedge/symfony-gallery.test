<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Action\Store;

use App\Entity\Gallery;
use App\Module\Gallery\Action\Store\PinGalleryAction as TestableAction;
use App\Module\Gallery\Dto\Action\Store\PinGalleryDto;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class PinGalleryActionTest extends TestCase
{
    public function testShouldPin(): void
    {
        $gallery = new class extends Gallery {
            public function getId(): int
            {
                return 1;
            }
        };

        $gallery->setIsPinned(false);

        $dto = new PinGalleryDto(true);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('flush')
        ;
        $action = new TestableAction($entityManager);

        $action->execute($gallery, $dto);

        self::assertTrue($gallery->isPinned());
        self::assertNotNull($gallery->getPinnedAt());
    }

    public function testShouldUnpin(): void
    {
        $gallery = new class extends Gallery {
            public function getId(): int
            {
                return 1;
            }
        };

        $gallery->setIsPinned(true);
        $gallery->setPinnedAt(CarbonImmutable::now());

        $dto = new PinGalleryDto(false);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('flush')
        ;
        $action = new TestableAction($entityManager);

        $action->execute($gallery, $dto);

        self::assertFalse($gallery->isPinned());
        self::assertNull($gallery->getPinnedAt());
    }
}
