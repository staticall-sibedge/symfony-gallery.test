<?php declare(strict_types=1);

namespace App\Http\Controller\File;

use App\Http\Controller\ControllerAbstract;
use App\Module\File\Action\Store\UploadFileAction;
use App\Module\File\Action\Store\UploadFileActionInterface;
use App\Module\File\Dto\Action\Store\UploadFileDto;
use App\Module\File\Message\FileUploadedMessage;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UploadAction extends ControllerAbstract
{
    public function __construct(
        #[Autowire(service: UploadFileAction::class)]
        private readonly UploadFileActionInterface $action,
        private readonly MessageBusInterface $bus,
        private readonly ValidatorInterface $validator,
    )
    {
        parent::__construct($this->validator);
    }

    #[Route(path: '/file/upload', name: 'file:upload', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = UploadFileDto::fromRequest($request);
        $this->ensureDtoValidity($dto);

        $file = $this->action->execute($dto);

        $this->bus->dispatch(new FileUploadedMessage($file->getId()));

        return new JsonResponse($file);
    }
}
