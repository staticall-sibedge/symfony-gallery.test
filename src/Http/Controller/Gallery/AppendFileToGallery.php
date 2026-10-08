<?php declare(strict_types=1);

namespace App\Http\Controller\Gallery;

use App\Module\File\Enum\FileStatusEnum;
use App\Module\File\Repository\FileRepositoryInterface;
use App\Module\Gallery\Action\Store\UpdateGalleryAction;
use App\Module\Gallery\Action\Store\UpdateGalleryActionInterface;
use App\Module\Gallery\Action\Store\UploadImageToGalleryAction;
use App\Module\Gallery\Action\Store\UploadImageToGalleryActionInterface;
use App\Module\Gallery\Dto\Action\Store\UploadImageToGalleryDto;
use App\Module\Gallery\Repository\GalleryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class AppendFileToGallery extends AbstractController
{
    public function __construct(
        #[Autowire(service: UploadImageToGalleryAction::class)]
        private readonly UploadImageToGalleryActionInterface $action,
        private readonly GalleryRepositoryInterface $galleryRepository,
        private readonly FileRepositoryInterface $fileRepository,
    )
    {
    }

    #[Route(path: '/gallery/{id}/append/{fileId}', name: 'gallery:append-file', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $gallery = $this->galleryRepository->findOneById((int) $request->attributes->get('id'));
        $file = $this->fileRepository->findOneById((string) $request->attributes->get('fileId'), FileStatusEnum::CHECKED);
        $dto = UploadImageToGalleryDto::fromRequest($request);

        return new JsonResponse($this->action->execute($gallery, $file, $dto));
    }
}
