<?php declare(strict_types=1);

namespace App\Module\File\Action\Store;

use App\Entity\File;
use App\Module\File\Action\Store\UploadFileAction\FileCantBeMovedException;
use App\Module\File\Action\Store\UploadFileAction\FileNameIsUnsafeException;
use App\Module\File\Action\Store\UploadFileAction\UnknownExtensionException;
use App\Module\File\Dto\Action\Store\UploadFileDtoInterface;
use App\Module\File\Enum\FileStatusEnum;
use Doctrine\ORM\EntityManagerInterface;
use League\Flysystem\FilesystemException;
use League\Flysystem\FilesystemOperator;
use Psr\Log\LoggerInterface;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\String\Slugger\SluggerInterface;

final class UploadFileAction implements UploadFileActionInterface
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly SluggerInterface $slugger,
        private readonly LoggerInterface $logger,

        #[Target('file.storage')]
        private readonly FilesystemOperator $storage,
    )
    {
    }

    public function execute(UploadFileDtoInterface $actionDto): File
    {
        $file = new File();

        $uploadedFile = $actionDto->uploadedFile;
        $extension = $uploadedFile->guessExtension();

        if ($extension === null) {
            throw new UnknownExtensionException('Uploaded file extension is unsupported');
        }

        $originalFilename = pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug(trim($originalFilename, ' .'))->toString();

        if ($safeFilename === '') {
            throw new FileNameIsUnsafeException('Provided file name can\'t be used');
        }

        $targetFilename = $safeFilename . '.' . $extension;

        $file
            ->setPath($targetFilename)
            ->setExtension($extension)
            ->setMimeType($uploadedFile->getMimeType())
            ->setStatus(FileStatusEnum::UNPROCESSED)
        ;

        try {
            $this->storage->move($uploadedFile->getClientOriginalPath(), $file->getPath());
        } catch (FilesystemException $e) {
            $this->logger->error($e);

            throw new FileCantBeMovedException(
                message: sprintf('File can\'t be moved to a target location "%s" with name "%s"', $file->getPath(), $targetFilename),
                previous: $e,
            );
        }

        $this->entityManager->persist($file);
        $this->entityManager->flush();

        return $file;
    }
}
