<?php declare(strict_types=1);

namespace AppTests\Unit\Module\File\Action\Store;

use App\Module\File\Action\Store\UploadFileAction as TestableAction;
use App\Module\File\Action\Store\UploadFileAction\FileCantBeMovedException;
use App\Module\File\Action\Store\UploadFileAction\FileNameIsUnsafeException;
use App\Module\File\Action\Store\UploadFileAction\UnknownExtensionException;
use App\Module\File\Dto\Action\Store\UploadFileDto;
use App\Module\File\Enum\FileStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemOperator;
use League\Flysystem\UnableToMoveFile;
use LogicException;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\String\UnicodeString;

final class UploadFileActionTest extends TestCase
{
    public function testShouldUploadFileSuccessfully(): void
    {
        $dto = new UploadFileDto(new UploadedFile(__DIR__ . '/UploadFileAction/test-image-png.png', 'test-image-png.png', 'image/png', null, true));

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

        $slugger = $this->createMock(SluggerInterface::class);

        $slugger
            ->expects($this->once())
            ->method('slug')
            ->withAnyParameters()
            ->willReturn(new UnicodeString('safe-str'))
        ;

        $logger = new NullLogger();
        $storage = $this->createMock(FilesystemOperator::class);

        $storage
            ->expects($this->once())
            ->method('move')
            ->withAnyParameters()
        ;

        $action = new TestableAction($entityManager, $slugger, $logger, $storage);

        $file = $action->execute($dto);

        self::assertSame('safe-str.png', $file->getPath());
        self::assertSame('png', $file->getExtension());
        self::assertSame('image/png', $file->getMimeType());
        self::assertSame(FileStatusEnum::UNPROCESSED, $file->getStatus());
        self::assertNull($file->getProcessedAt());
        self::assertNull($file->getDeletedAt());
    }

    public function testSluggerCantProcessFileNameForSomeReason(): void
    {
        $dto = new UploadFileDto(new UploadedFile(__DIR__ . '/UploadFileAction/test-image-png.png', 'test-image-png.png', 'image/png', null, true));

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager
            ->expects($this->never())
            ->method('persist')
            ->withAnyParameters()
        ;
        $entityManager
            ->expects($this->never())
            ->method('flush')
        ;

        $slugger = $this->createMock(SluggerInterface::class);

        $slugger
            ->expects($this->once())
            ->method('slug')
            ->withAnyParameters()
            ->willThrowException(new LogicException('Test exception'))
        ;

        $logger = $this->createMock(LoggerInterface::class);

        $logger
            ->expects($this->never())
            ->method('error')
            ->withAnyParameters()
        ;

        $storage = $this->createMock(FilesystemOperator::class);

        $storage
            ->expects($this->never())
            ->method('move')
            ->withAnyParameters()
        ;

        $action = new TestableAction($entityManager, $slugger, $logger, $storage);

        $this->expectException(LogicException::class);
        $action->execute($dto);
    }

    public function testCantMoveTheFile(): void
    {
        $dto = new UploadFileDto(new UploadedFile(__DIR__ . '/UploadFileAction/test-image-png.png', 'test-image-png.png', 'image/png', null, true));

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager
            ->expects($this->never())
            ->method('persist')
            ->withAnyParameters()
        ;
        $entityManager
            ->expects($this->never())
            ->method('flush')
        ;

        $slugger = $this->createMock(SluggerInterface::class);

        $slugger
            ->expects($this->once())
            ->method('slug')
            ->withAnyParameters()
            ->willReturn(new UnicodeString('safe-str'))
        ;

        $logger = $this->createMock(LoggerInterface::class);

        $logger
            ->expects($this->once())
            ->method('error')
            ->withAnyParameters()
        ;

        $storage = $this->createMock(FilesystemOperator::class);

        $storage
            ->expects($this->once())
            ->method('move')
            ->withAnyParameters()
            ->willThrowException(new UnableToMoveFile('test exception'))
        ;

        $action = new TestableAction($entityManager, $slugger, $logger, $storage);

        $this->expectException(FileCantBeMovedException::class);
        $action->execute($dto);
    }

    public function testEmptyExtensionShouldTriggerError(): void
    {
        $dto = new UploadFileDto(new UploadedFile(__DIR__ . '/UploadFileAction/empty-file', 'empty-file', null, null, true));

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager
            ->expects($this->never())
            ->method('persist')
            ->withAnyParameters()
        ;
        $entityManager
            ->expects($this->never())
            ->method('flush')
        ;

        $slugger = $this->createMock(SluggerInterface::class);

        $slugger
            ->expects($this->never())
            ->method('slug')
            ->withAnyParameters()
            ->willReturn(new UnicodeString('test-str'))
        ;

        $logger = new NullLogger();
        $storage = $this->createMock(FilesystemOperator::class);

        $storage
            ->expects($this->never())
            ->method('move')
            ->withAnyParameters()
        ;

        $action = new TestableAction($entityManager, $slugger, $logger, $storage);

        $this->expectException(UnknownExtensionException::class);
        $action->execute($dto);
    }

    public function testUnsafeFilenameError(): void
    {
        $dto = new UploadFileDto(new UploadedFile(__DIR__ . '/UploadFileAction/test-image-png.png', '.', null, null, true));

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $entityManager
            ->expects($this->never())
            ->method('persist')
            ->withAnyParameters()
        ;
        $entityManager
            ->expects($this->never())
            ->method('flush')
        ;

        $slugger = $this->createMock(SluggerInterface::class);

        $slugger
            ->expects($this->once())
            ->method('slug')
            ->withAnyParameters()
            ->willReturn(new UnicodeString(''))
        ;

        $logger = new NullLogger();
        $storage = $this->createMock(FilesystemOperator::class);

        $storage
            ->expects($this->never())
            ->method('move')
            ->withAnyParameters()
        ;

        $action = new TestableAction($entityManager, $slugger, $logger, $storage);

        $this->expectException(FileNameIsUnsafeException::class);
        $action->execute($dto);
    }
}
