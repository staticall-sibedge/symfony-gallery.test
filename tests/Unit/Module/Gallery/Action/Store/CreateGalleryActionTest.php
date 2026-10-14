<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Action\Store;

use App\Module\Gallery\Action\Store\CreateGalleryAction as TestableAction;
use App\Module\Gallery\Dto\Action\Store\CreateGalleryDto;
use App\Module\Gallery\Enum\GalleryStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class CreateGalleryActionTest extends TestCase
{
    public function testShouldUpdateGalleryWithNewData(): void
    {
        $dto = new CreateGalleryDto('Testing creating gallery', null, GalleryStatusEnum::PUBLISHED->value);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('persist')
            ->withAnyParameters()
        ;
        $entityManager
            ->expects($this->once())
            ->method('flush')
        ;
        $action = new TestableAction($entityManager);

        $gallery = $action->execute($dto);

        self::assertSame('Testing creating gallery', $gallery->getName());
        self::assertNull($gallery->getDescription());
        self::assertSame(GalleryStatusEnum::PUBLISHED, $gallery->getStatus());
        self::assertNull($gallery->getDeletedAt());
    }
}
