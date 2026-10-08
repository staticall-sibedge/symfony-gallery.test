<?php declare(strict_types=1);

namespace App\Entity;

use Carbon\CarbonImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Types\UuidType;

#[ORM\Entity()]
#[ORM\Table(name: GalleryImage::TABLE_NAME)]
#[ORM\Index(name: 'idx_sort_order', columns: [GalleryImage::COLUMN_SORT_ORDER])]
#[Gedmo\SoftDeleteable(timeAware: true)]
class GalleryImage
{
    public const string TABLE_NAME = 'gallery_images';

    public const string COLUMN_ID = 'id';
    public const string COLUMN_SORT_ORDER = 'sort_order';
    public const string COLUMN_GALLERY_ID = 'gallery_id';
    public const string COLUMN_FILE_ID = 'file_id';
    public const string COLUMN_CREATED_AT = 'created_at';
    public const string COLUMN_UPDATED_AT = 'updated_at';
    public const string COLUMN_DELETED_AT = 'deleted_at';

    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(name: self::COLUMN_ID, type: Types::INTEGER, length: 11)]
    private int $id;

    #[ORM\Column(name: self::COLUMN_SORT_ORDER, type: Types::INTEGER, length: 10, options: ['unsigned' => true])]
    #[Gedmo\SortablePosition()]
    private int $sortOrder;

    #[ORM\Column(name: self::COLUMN_GALLERY_ID, type: Types::INTEGER, length: 11, options: ['unsigned' => true])]
    #[ORM\OneToOne(targetEntity: Gallery::class)]
    #[ORM\JoinColumn(name: 'gallery_id', referencedColumnName: 'id')]
    private Gallery $gallery;

    #[ORM\Column(name: self::COLUMN_FILE_ID, type: UuidType::NAME)]
    #[ORM\OneToOne(targetEntity: File::class)]
    #[ORM\JoinColumn(name: 'file_id', referencedColumnName: 'id')]
    private File $file;

    #[ORM\Column(name: self::COLUMN_CREATED_AT, type: Types::DATETIME_UTC_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private CarbonImmutable $createdAt;

    #[ORM\Column(name: self::COLUMN_UPDATED_AT, type: Types::DATETIME_UTC_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private CarbonImmutable $updatedAt;

    #[ORM\Column(name: self::COLUMN_DELETED_AT, type: Types::DATETIME_UTC_IMMUTABLE, nullable: true)]
    private ?CarbonImmutable $deletedAt = null;

    public function __construct()
    {
        $this->createdAt = CarbonImmutable::now();
        $this->updatedAt = CarbonImmutable::now();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSortOrder(): int
    {
        return $this->sortOrder;
    }

    public function setSortOrder(int $sortOrder): static
    {
        $this->sortOrder = $sortOrder;

        return $this;
    }

    public function getFile(): File
    {
        return $this->file;
    }

    public function setFile(File $file): static
    {
        $this->file = $file;

        return $this;
    }

    public function getGallery(): Gallery
    {
        return $this->gallery;
    }

    public function setGallery(Gallery $gallery): static
    {
        $this->gallery = $gallery;

        return $this;
    }

    public function getCreatedAt(): CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): CarbonImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(CarbonImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDeletedAt(): CarbonImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(CarbonImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
