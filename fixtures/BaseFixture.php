<?php declare(strict_types=1);

namespace App\Fixtures;

use App\Entity\File;
use App\Entity\Gallery;
use App\Entity\GalleryImage;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\PropertyNormalizer;

abstract class BaseFixture extends Fixture
{
    public function __construct(
        private ?DenormalizerInterface $denormalizer = null,
    )
    {
        if ($this->denormalizer === null) {
            $this->denormalizer = new PropertyNormalizer();
        }
    }

    protected function addGallery(ObjectManager $manager, array $data): Gallery
    {
        $data['name'] ??= 'Test gallery';
        $gallery = $this->denormalizer->denormalize($data, Gallery::class);

        $manager->persist($gallery);

        return $gallery;
    }

    protected function addFile(ObjectManager $manager, array $data): File
    {
        $data['path'] ??= '/test.path';
        $data['extension'] ??= 'txt';

        $file = $this->denormalizer->denormalize($data, File::class);

        $manager->persist($file);

        return $file;
    }

    protected function addGalleryImage(ObjectManager $manager, Gallery $gallery, File $file, array $data): File
    {
        $galleryImage = $this->denormalizer->denormalize($data, GalleryImage::class);

        $galleryImage->setGallery($gallery);
        $galleryImage->setFile($file);

        $manager->persist($galleryImage);

        return $file;
    }
}
