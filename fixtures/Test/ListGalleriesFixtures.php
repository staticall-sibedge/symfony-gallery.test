<?php declare(strict_types=1);

namespace App\Fixtures\Test;

use App\Module\Gallery\Enum\GalleryStatusEnum;
use App\Fixtures\BaseFixture;
use Doctrine\Persistence\ObjectManager;

final class ListGalleriesFixtures extends BaseFixture
{
    public function load(ObjectManager $manager): void
    {
        $this->addGallery($manager, ['isPinned' => false, 'status' => GalleryStatusEnum::DRAFT]);
        $this->addGallery($manager, ['isPinned' => false, 'status' => GalleryStatusEnum::PUBLISHED]);
        $this->addGallery($manager, ['isPinned' => true, 'status' => GalleryStatusEnum::DRAFT]);
        $this->addGallery($manager, ['isPinned' => true, 'status' => GalleryStatusEnum::PUBLISHED]);

        $manager->flush();
    }
}
