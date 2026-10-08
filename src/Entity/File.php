<?php declare(strict_types=1);

namespace App\Entity;

use App\Module\File\Enum\FileStatusEnum;
use App\Module\File\Repository\FileRepository;
use Carbon\CarbonImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Types\UuidType;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: FileRepository::class)]
#[ORM\Table(name: File::TABLE_NAME)]
#[ORM\UniqueConstraint(name: 'udx_path', columns: [File::COLUMN_PATH])]
#[ORM\Index(name: 'idx_status_deleted', columns: [File::COLUMN_STATUS, File::COLUMN_DELETED_AT])]
#[Gedmo\SoftDeleteable(timeAware: true)]
class File
{
    public const string TABLE_NAME = 'files';

    public const string COLUMN_ID = 'id';
    public const string COLUMN_PATH = 'path';
    public const string COLUMN_EXTENSION = 'extension';
    public const string COLUMN_MIME_TYPE = 'mimeType';
    public const string COLUMN_STATUS = 'status';
    public const string COLUMN_CREATED_AT = 'createdAt';
    public const string COLUMN_UPDATED_AT = 'updatedAt';
    public const string COLUMN_PROCESSED_AT = 'processedAt';
    public const string COLUMN_DELETED_AT = 'deletedAt';

    #[ORM\Id()]
    #[ORM\Column(name: self::COLUMN_ID, type: UuidType::NAME)]
    private Uuid $id;

    #[ORM\Column(name: self::COLUMN_PATH, type: Types::STRING, length: 255)]
    private string $path;

    #[ORM\Column(name: self::COLUMN_EXTENSION, type: Types::STRING, length: 16)]
    private string $extension;

    #[ORM\Column(name: self::COLUMN_MIME_TYPE, type: Types::STRING, length: 255, nullable: true)]
    private ?string $mimeType = null;

    #[ORM\Column(name: self::COLUMN_STATUS, type: Types::STRING, length: 32, enumType: FileStatusEnum::class)]
    private FileStatusEnum $status = FileStatusEnum::UNPROCESSED;

    #[ORM\Column(name: self::COLUMN_CREATED_AT, type: Types::DATETIME_UTC_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'create')]
    private CarbonImmutable $createdAt;

    #[ORM\Column(name: self::COLUMN_UPDATED_AT, type: Types::DATETIME_UTC_IMMUTABLE)]
    #[Gedmo\Timestampable(on: 'update')]
    private CarbonImmutable $updatedAt;

    #[ORM\Column(name: self::COLUMN_PROCESSED_AT, type: Types::DATETIME_UTC_IMMUTABLE, nullable: true)]
    private ?CarbonImmutable $processedAt = null;

    #[ORM\Column(name: self::COLUMN_DELETED_AT, type: Types::DATETIME_UTC_IMMUTABLE, nullable: true)]
    private ?CarbonImmutable $deletedAt = null;

    public function __construct()
    {
        $this->id = Uuid::v7();
        $this->createdAt = CarbonImmutable::now();
        $this->updatedAt = CarbonImmutable::now();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): static
    {
        $this->path = $path;

        return $this;
    }

    public function getExtension(): string
    {
        return $this->extension;
    }

    public function setExtension(string $extension): static
    {
        $this->extension = $extension;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    public function setMimeType(?string $mimeType): static
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    public function getStatus(): FileStatusEnum
    {
        return $this->status;
    }

    public function setStatus(FileStatusEnum $status): static
    {
        $this->status = $status;

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

    public function getProcessedAt(): ?CarbonImmutable
    {
        return $this->processedAt;
    }

    public function setProcessedAt(CarbonImmutable $processedAt): static
    {
        $this->processedAt = $processedAt;

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
