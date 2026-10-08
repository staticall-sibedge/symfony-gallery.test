<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Action\Query;

use App\Entity\Gallery;
use App\Module\Gallery\Action\Query\ListGalleriesAction as TestableAction;
use App\Module\Gallery\Dto\Action\Query\ListGalleriesFilterDto;
use App\Module\Gallery\Enum\GalleryStatusEnum;
use AppTests\Unit\Module\Gallery\Action\WithFakeRepositoryTestCase;
use Doctrine\Common\Collections\ArrayCollection;

final class ListGalleriesActionTest extends WithFakeRepositoryTestCase
{
    public function testShouldHandleEmptyFilter(): void
    {
        $galleryA = new class extends Gallery {
            public function getId(): int
            {
                return 1;
            }
        };
        $galleryB = new class extends Gallery {
            public function getId(): int
            {
                return 2;
            }
        };

        $galleriesCollection = new ArrayCollection();

        $galleriesCollection->add($galleryA);
        $galleriesCollection->add($galleryB);

        $repository = $this->getRepositoryMock($galleriesCollection);

        $action = new TestableAction($repository);
        $dto = new ListGalleriesFilterDto();

        self::assertSame($galleriesCollection, $action->execute($dto));
    }

    public function testShouldHandleEmptyCollection(): void
    {
        $galleriesCollection = new ArrayCollection();

        $repository = $this->getRepositoryMock($galleriesCollection);

        $action = new TestableAction($repository);
        $dto = new ListGalleriesFilterDto();

        self::assertSame($galleriesCollection, $action->execute($dto));
    }

    public function testShouldApplyFilter(): void
    {
        $galleryA = new class extends Gallery {
            public function getId(): int
            {
                return 1;
            }
        };
        $galleryB = new class extends Gallery {
            public function getId(): int
            {
                return 2;
            }
        };
        $galleryA->setIsPinned(true);
        $galleryA->setStatus(GalleryStatusEnum::PUBLISHED);
        $galleryB->setIsPinned(false);
        $galleryB->setStatus(GalleryStatusEnum::DRAFT);

        $galleriesCollection = new ArrayCollection();

        $galleriesCollection->add($galleryA);
        $galleriesCollection->add($galleryB);

        $repository = $this->getRepositoryMock($galleriesCollection);

        $action = new TestableAction($repository);

        $dto = new ListGalleriesFilterDto(false, GalleryStatusEnum::PUBLISHED->value);
        self::assertSame(0, $action->execute($dto)->count());

        $dto = new ListGalleriesFilterDto(true, GalleryStatusEnum::PUBLISHED->value);
        $result = $action->execute($dto);
        self::assertSame(1, $result->count());
        self::assertSame($galleryA, $result->first());

        $dto = new ListGalleriesFilterDto(false, GalleryStatusEnum::DRAFT->value);
        $result = $action->execute($dto);
        self::assertSame(1, $result->count());
        self::assertSame($galleryB, $result->first());
    }
}
