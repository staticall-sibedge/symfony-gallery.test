<?php declare(strict_types=1);

namespace App\Http\Controller\Gallery;

use App\Module\Gallery\Action\Store\CreateGalleryAction;
use App\Module\Gallery\Action\Store\CreateGalleryActionInterface;
use App\Module\Gallery\Dto\Action\Store\CreateGalleryDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;

final class ListAction extends AbstractController
{
    public function __construct(
        #[Autowire(service: CreateGalleryAction::class)]
        private readonly CreateGalleryActionInterface $action,
    )
    {
    }

    #[Route(path: '/gallery', name: 'gallery:list', methods: ['GET'])]
    public function __invoke(#[MapRequestPayload] CreateGalleryDto $dto): JsonResponse
    {
        return new JsonResponse($this->action->execute($dto));
    }
}
