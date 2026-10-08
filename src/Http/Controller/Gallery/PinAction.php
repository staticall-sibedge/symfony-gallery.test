<?php declare(strict_types=1);

namespace App\Http\Controller\Gallery;

use App\Module\Gallery\Action\Store\PinGalleryAction;
use App\Module\Gallery\Action\Store\PinGalleryActionInterface;
use App\Module\Gallery\Dto\Action\Store\PinGalleryDto;
use App\Module\Gallery\Repository\GalleryRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class PinAction extends AbstractController
{
    public function __construct(
        #[Autowire(service: PinGalleryAction::class)]
        private readonly PinGalleryActionInterface $action,
        private readonly GalleryRepositoryInterface $repository,
    )
    {
    }

    #[Route(path: '/gallery/{id}/pin', name: 'gallery:pin', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $gallery = $this->repository->findOneById((int) $request->attributes->get('id'));
        $dto = PinGalleryDto::fromRequest($request);

        return new JsonResponse($this->action->execute($gallery, $dto));
    }
}
