<?php declare(strict_types=1);

namespace App\Entity;

use App\Module\Gallery\Enum\GalleryStatusEnum;
use App\Module\Gallery\Repository\GalleryRepository;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: GalleryRepository::class)]
#[ORM\Table(name: Gallery::TABLE_NAME)]
#[ORM\Index(name: 'idx_status_pinned_deleted', columns: [Gallery::COLUMN_STATUS, Gallery::COLUMN_IS_PINNED, Gallery::COLUMN_DELETED_AT])]
#[Gedmo\SoftDeleteable(timeAware: true)]
class Gallery
{
    public const string TABLE_NAME = 'galleries';

    public const string COLUMN_ID = 'id';
    public const string COLUMN_NAME = 'name';
    public const string COLUMN_DESCRIPTION = 'description';
    public const string COLUMN_STATUS = 'status';
    public const string COLUMN_IS_PINNED = 'is_pinned';
    public const string COLUMN_CREATED_AT = 'created_at';
    public const string COLUMN_UPDATED_AT = 'updated_at';
    public const string COLUMN_DELETED_AT = 'deleted_at';
    public const string COLUMN_PINNED_AT = 'pinned_at';

    #[ORM\Id()]
    #[ORM\GeneratedValue()]
    #[ORM\Column(name: self::COLUMN_ID, type: Types::INTEGER, length: 11, options: ['unsigned' => true])]
    private int $id;

    #[ORM\Column(name: self::COLUMN_NAME, type: Types::STRING, length: 64)]
    private string $name;

    #[ORM\Column(name: self::COLUMN_DESCRIPTION, type: Types::STRING, length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(name: self::COLUMN_STATUS, type: Types::STRING, length: 32, enumType: GalleryStatusEnum::class)]
    private GalleryStatusEnum $status = GalleryStatusEnum::DRAFT;

    #[ORM\Column(name: self::COLUMN_IS_PINNED, type: Types::BOOLEAN)]
    private bool $isPinned = false;

    #[ORM\Column(name: self::COLUMN_CREATED_AT, type: Types::DATETIME_UTC_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private CarbonImmutable $createdAt;

    #[ORM\Column(name: self::COLUMN_UPDATED_AT, type: Types::DATETIME_UTC_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private CarbonImmutable $updatedAt;

    #[ORM\Column(name: self::COLUMN_DELETED_AT, type: Types::DATETIME_UTC_IMMUTABLE, nullable: true)]
    private ?CarbonImmutable $deletedAt = null;

    #[ORM\Column(name: self::COLUMN_PINNED_AT, type: Types::DATETIME_UTC_IMMUTABLE, nullable: true)]
    private ?CarbonImmutable $pinnedAt = null;

    private Collection $images;

    public function __construct()
    {
        $this->images = new ArrayCollection();
        $this->createdAt = CarbonImmutable::now();
        $this->updatedAt = CarbonImmutable::now();
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getImages(): Collection
    {
        return $this->images;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): GalleryStatusEnum
    {
        return $this->status;
    }

    public function setStatus(GalleryStatusEnum $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function isPinned(): bool
    {
        return $this->isPinned;
    }

    public function setIsPinned(bool $isPinned): static
    {
        $this->isPinned = $isPinned;

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

    public function getDeletedAt(): ?CarbonImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(CarbonImmutable $deletedAt): static
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }

    public function getPinnedAt(): ?CarbonImmutable
    {
        return $this->pinnedAt;
    }

    public function setPinnedAt(CarbonImmutable $pinnedAt): static
    {
        $this->pinnedAt = $pinnedAt;

        return $this;
    }
}
