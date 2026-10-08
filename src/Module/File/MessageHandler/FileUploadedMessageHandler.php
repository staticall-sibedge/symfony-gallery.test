<?php declare(strict_types=1);

namespace App\Module\File\MessageHandler;

use App\Module\File\Enum\FileStatusEnum;
use App\Module\File\Message\FileUploadedMessage;
use App\Module\File\Repository\FileRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class FileUploadedMessageHandler
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly FileRepositoryInterface $repository,
    )
    {
    }

    public function __invoke(FileUploadedMessage $message): void
    {
        $file = $this->repository->findOneById($message->fileId);

        // @todo Perform antivirus scan and other checks needed

        $file->setStatus(FileStatusEnum::CHECKED);

        $this->entityManager->flush();
    }
}
