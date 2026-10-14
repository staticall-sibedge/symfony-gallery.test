<?php declare(strict_types=1);

namespace App\Http\Controller\Gallery;

use App\Http\Controller\ControllerAbstract;
use App\Module\File\Enum\FileStatusEnum;
use App\Module\File\Repository\FileRepositoryInterface;
use App\Module\Gallery\Action\Store\AppendFileToGalleryAction;
use App\Module\Gallery\Action\Store\AppendFileToGalleryActionInterface;
use App\Module\Gallery\Dto\Action\Store\AppendFileToGalleryDto;
use App\Module\Gallery\Repository\GalleryRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class AppendFileToGallery extends ControllerAbstract
{
    public function __construct(
        #[Autowire(service: AppendFileToGalleryAction::class)]
        private readonly AppendFileToGalleryActionInterface $action,
        private readonly GalleryRepositoryInterface         $galleryRepository,
        private readonly FileRepositoryInterface            $fileRepository,
        private readonly ValidatorInterface                 $validator,
    )
    {
        parent::__construct($this->validator);
    }

    #[Route(path: '/gallery/{id}/append/{fileId}', name: 'gallery:append-file', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $gallery = $this->galleryRepository->findOneById((int) $request->attributes->get('id'));
        $file = $this->fileRepository->findOneById((string) $request->attributes->get('fileId'), FileStatusEnum::CHECKED);
        $dto = AppendFileToGalleryDto::fromRequest($request);
        $this->ensureDtoValidity($dto);

        return new JsonResponse($this->action->execute($gallery, $file, $dto));
    }
}
