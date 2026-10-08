<?php declare(strict_types=1);

namespace AppTests\Unit\Module\Gallery\Action\Query;

use App\Entity\Gallery;
use App\Module\Gallery\Action\Query\SingleGalleryAction as TestableAction;
use App\Module\Gallery\Dto\Action\Query\SingleGalleryDto;
use AppTests\Unit\Module\Gallery\Action\WithFakeRepositoryTestCase;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\NoResultException;

final class SingleGalleryActionTest extends WithFakeRepositoryTestCase
{
    public function testShouldReturnPresetData(): void
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
        $dtoA = new SingleGalleryDto(1);
        $dtoB = new SingleGalleryDto(2);

        self::assertSame($galleryA, $action->execute($dtoA));
        self::assertSame($galleryB, $action->execute($dtoB));
    }

    public function testShouldThrowErrorIfNoDataExists(): void
    {
        $galleryA = new class extends Gallery {
            public function getId(): int
            {
                return 1;
            }
        };

        $galleriesCollection = new ArrayCollection();

        $galleriesCollection->add($galleryA);

        $repository = $this->getRepositoryMock($galleriesCollection);

        $action = new TestableAction($repository);
        $dto = new SingleGalleryDto(999);

        $this->expectException(NoResultException::class);

        self::assertSame($galleryA, $action->execute($dto));
    }
}
