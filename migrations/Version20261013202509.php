<?php declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\File;
use App\Entity\Gallery;
use App\Entity\GalleryImage;
use App\Module\File\Enum\FileStatusEnum;
use App\Module\Gallery\Enum\GalleryStatusEnum;
use Doctrine\DBAL\Schema\Column;
use Doctrine\DBAL\Schema\ForeignKeyConstraint;
use Doctrine\DBAL\Schema\Index;
use Doctrine\DBAL\Schema\Name\OptionallyQualifiedName;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Schema\Table;
use Doctrine\DBAL\Schema\UniqueConstraint;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Bridge\Doctrine\Types\UuidType;

final class Version20261013202509 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial setup';
    }

    public function up(Schema $schema): void
    {
        $editor = $schema->edit();

        if ($schema->hasTable(Gallery::TABLE_NAME) === false) {
            $tableGalleries = $this->makeGalleries();
        } else {
            $tableGalleries = $schema->getTable(Gallery::TABLE_NAME);
        }

        if ($schema->hasTable(File::TABLE_NAME) === false) {
            $tableFiles = $this->makeFiles();
        } else {
            $tableFiles = $schema->getTable(File::TABLE_NAME);
        }

        if ($schema->hasTable(GalleryImage::TABLE_NAME) === false) {
            $tableGalleryImages = $this->makeGalleryImages($tableGalleries, $tableFiles);
        } else {
            $tableGalleryImages = $schema->getTable(GalleryImage::TABLE_NAME);
        }

        $editor
            ->addTable($tableGalleries)
            ->addTable($tableFiles)
            ->addTable($tableGalleryImages)
        ;

        $schema = $editor->create();

        foreach ($schema->toSql($this->platform) as $sql) {
            // Doctrine is trying to recreate its versions table every time, even if that table already exists
            if (str_contains($sql, 'doctrine_migration_versions') === true && $schema->hasTable('doctrine_migration_versions') === true) {
                continue;
            }

            $this->addSql($sql);
        }
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable(GalleryImage::TABLE_NAME);
        $schema->dropTable(Gallery::TABLE_NAME);
        $schema->dropTable(File::TABLE_NAME);

        return;

        // this should work, but it actually doesn't
        $editor = $schema->edit();

        $editor
            ->dropTable(OptionallyQualifiedName::unquoted(GalleryImage::TABLE_NAME))
            ->dropTable(OptionallyQualifiedName::unquoted(Gallery::TABLE_NAME))
            ->dropTable(OptionallyQualifiedName::unquoted(File::TABLE_NAME))
        ;

        $schema = $editor->create();

        foreach ($schema->toSql($this->platform) as $sql) {
            var_dump('sql', $sql); // we'll only have SQL to generate migration metadata table, go figure
            // Doctrine is trying to recreate its versions table every time, even if that table already exists
            if (str_contains($sql, 'doctrine_migration_versions') === true && $schema->hasTable('doctrine_migration_versions') === true) {
                continue;
            }

            $this->addSql($sql);
        }
    }

    private function makeGalleries(): Table
    {
        $editor = Table::editor();

        $editor->setName(OptionallyQualifiedName::unquoted(Gallery::TABLE_NAME));
        $editor->addColumn(new Column(Gallery::COLUMN_ID, Types::INTEGER, ['length' => 11, 'unsigned' => true]));
        $editor->addColumn(new Column(Gallery::COLUMN_NAME, Types::STRING, ['length' => 64]));
        $editor->addColumn(new Column(Gallery::COLUMN_DESCRIPTION, Types::STRING, ['length' => 255, 'notnull' => true]));
        $editor->addColumn(new Column(Gallery::COLUMN_STATUS, Types::STRING, ['length' => 32, 'default' => GalleryStatusEnum::DRAFT->value, 'platformOptions' => ['enumType' => GalleryStatusEnum::class]]));
        $editor->addColumn(new Column(Gallery::COLUMN_IS_PINNED, Types::BOOLEAN, ['default' => false]));
        $editor->addColumn(new Column(Gallery::COLUMN_CREATED_AT, Types::DATETIME_UTC_IMMUTABLE));
        $editor->addColumn(new Column(Gallery::COLUMN_UPDATED_AT, Types::DATETIME_UTC_IMMUTABLE));
        $editor->addColumn(new Column(Gallery::COLUMN_DELETED_AT, Types::DATETIME_UTC_IMMUTABLE, ['notnull' => true]));
        $editor->addColumn(new Column(Gallery::COLUMN_PINNED_AT, Types::DATETIME_UTC_IMMUTABLE, ['notnull' => true]));

        $editor->addIndex(new Index('PRIMARY_KEY', [GalleryImage::COLUMN_ID], false, true));
        $editor->addIndex(new Index('idx_status_pinned_deleted', [Gallery::COLUMN_STATUS, Gallery::COLUMN_IS_PINNED, Gallery::COLUMN_DELETED_AT]));

        return $editor->create();
    }

    private function makeFiles(): Table
    {
        $editor = Table::editor();

        $editor->setName(OptionallyQualifiedName::unquoted(File::TABLE_NAME));
        $editor->addColumn(new Column(File::COLUMN_ID, UuidType::NAME));
        $editor->addColumn(new Column(File::COLUMN_PATH, Types::STRING, ['length' => 255]));
        $editor->addColumn(new Column(File::COLUMN_EXTENSION, Types::STRING, ['length' => 16]));
        $editor->addColumn(new Column(File::COLUMN_MIME_TYPE, Types::STRING, ['length' => 255, 'notnull' => true]));
        $editor->addColumn(new Column(File::COLUMN_STATUS, Types::STRING, ['length' => 32, 'default' => FileStatusEnum::UNPROCESSED->value, 'platformOptions' => ['enumType' => FileStatusEnum::class]]));
        $editor->addColumn(new Column(File::COLUMN_CREATED_AT, Types::DATETIME_UTC_IMMUTABLE));
        $editor->addColumn(new Column(File::COLUMN_UPDATED_AT, Types::DATETIME_UTC_IMMUTABLE));
        $editor->addColumn(new Column(File::COLUMN_DELETED_AT, Types::DATETIME_UTC_IMMUTABLE, ['notnull' => true]));
        $editor->addColumn(new Column(File::COLUMN_PROCESSED_AT, Types::DATETIME_UTC_IMMUTABLE, ['notnull' => true]));

        $editor->addUniqueConstraint(new UniqueConstraint('udx_path', [File::COLUMN_PATH]));
        $editor->addIndex(new Index('PRIMARY_KEY', [GalleryImage::COLUMN_ID], false, true));
        $editor->addIndex(new Index('idx_status_deleted', [File::COLUMN_STATUS, File::COLUMN_DELETED_AT]));

        return $editor->create();
    }

    private function makeGalleryImages(Table $tableGalleries, Table $tableFiles): Table
    {
        $editor = Table::editor();

        $editor->setName(OptionallyQualifiedName::unquoted(GalleryImage::TABLE_NAME));
        $editor->addColumn(new Column(GalleryImage::COLUMN_ID, Types::INTEGER, ['length' => 11, 'unsigned' => true]));
        $editor->addColumn(new Column(GalleryImage::COLUMN_SORT_ORDER, Types::STRING, ['length' => 255, 'notnull' => true, 'unsigned' => true]));
        $editor->addColumn(new Column(GalleryImage::COLUMN_GALLERY_ID, Types::INTEGER, ['length' => 11, 'unsigned' => true]));
        $editor->addColumn(new Column(GalleryImage::COLUMN_FILE_ID, UuidType::NAME));
        $editor->addColumn(new Column(GalleryImage::COLUMN_CREATED_AT, Types::DATETIME_UTC_IMMUTABLE));
        $editor->addColumn(new Column(GalleryImage::COLUMN_UPDATED_AT, Types::DATETIME_UTC_IMMUTABLE));
        $editor->addColumn(new Column(GalleryImage::COLUMN_DELETED_AT, Types::DATETIME_UTC_IMMUTABLE, ['notnull' => true]));

        $editor->addUniqueConstraint(new UniqueConstraint('udx_file_gallery', [GalleryImage::COLUMN_FILE_ID, GalleryImage::COLUMN_GALLERY_ID]));
        $editor->addIndex(new Index('PRIMARY_KEY', [GalleryImage::COLUMN_ID], false, true));
        $editor->addIndex(new Index('idx_gallery', [GalleryImage::COLUMN_GALLERY_ID]));
        $editor->addIndex(new Index('idx_sort_order', [GalleryImage::COLUMN_SORT_ORDER]));

        $editor->addForeignKeyConstraint(
            new ForeignKeyConstraint(
                [GalleryImage::COLUMN_GALLERY_ID],
                $tableGalleries->getObjectName()->toString(),
                [Gallery::COLUMN_ID],
                '',
                [
                    'onUpdate' => ForeignKeyConstraint\ReferentialAction::CASCADE->value,
                    'onDelete' => ForeignKeyConstraint\ReferentialAction::NO_ACTION->value,
                ],
            ),
        );

        $editor->addForeignKeyConstraint(
            new ForeignKeyConstraint(
                [GalleryImage::COLUMN_FILE_ID],
                $tableFiles->getObjectName()->toString(),
                [File::COLUMN_ID],
                '',
                [
                    'onUpdate' => ForeignKeyConstraint\ReferentialAction::CASCADE->value,
                    'onDelete' => ForeignKeyConstraint\ReferentialAction::NO_ACTION->value,
                ],
            ),
        );

        return $editor->create();
    }
}
