<?php declare(strict_types=1);

namespace App\Http\Controller\Gallery;

use App\Module\Gallery\Action\Store\CreateGalleryAction;
use App\Module\Gallery\Action\Store\CreateGalleryActionInterface;
use App\Module\Gallery\Dto\Action\Store\CreateGalleryDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

final class CreateAction extends AbstractController
{
    public function __construct(
        #[Autowire(service: CreateGalleryAction::class)]
        private readonly CreateGalleryActionInterface $action,
    )
    {
    }

    #[Route(path: '/gallery', name: 'gallery:create', methods: ['POST'])]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = CreateGalleryDto::fromRequest($request);

        return new JsonResponse($this->action->execute($dto));
    }
}
