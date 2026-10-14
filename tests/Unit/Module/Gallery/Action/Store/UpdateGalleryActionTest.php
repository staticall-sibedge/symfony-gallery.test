<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Action\Store;

use App\Entity\Gallery;
use App\Module\Gallery\Action\Store\UpdateGalleryAction as TestableAction;
use App\Module\Gallery\Dto\Action\Store\UpdateGalleryDto;
use App\Module\Gallery\Enum\GalleryStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

final class UpdateGalleryActionTest extends TestCase
{
    public function testShouldUpdateGalleryWithNewData(): void
    {
        $gallery = new class extends Gallery {
            public function getId(): int
            {
                return 1;
            }
        };

        $gallery->setName('old name');
        $gallery->setDescription('old description');
        $gallery->setStatus(GalleryStatusEnum::DRAFT);

        $dto = new UpdateGalleryDto('Testing updating gallery', null, GalleryStatusEnum::PUBLISHED->value);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->expects($this->once())
            ->method('flush')
        ;
        $action = new TestableAction($entityManager);

        $action->execute($gallery, $dto);

        self::assertSame('Testing updating gallery', $gallery->getName());
        self::assertNull($gallery->getDescription());
        self::assertSame(GalleryStatusEnum::PUBLISHED, $gallery->getStatus());
        self::assertNull($gallery->getDeletedAt());
    }
}
