<?php declare(strict_types=1);

namespace App\Http\Controller\Gallery;

use App\Http\Controller\ControllerAbstract;
use App\Module\Gallery\Action\Store\UpdateGalleryAction;
use App\Module\Gallery\Action\Store\UpdateGalleryActionInterface;
use App\Module\Gallery\Dto\Action\Store\UpdateGalleryDto;
use App\Module\Gallery\Repository\GalleryRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class UpdateAction extends ControllerAbstract
{
    public function __construct(
        #[Autowire(service: UpdateGalleryAction::class)]
        private readonly UpdateGalleryActionInterface $action,
        private readonly GalleryRepositoryInterface $repository,
        private readonly ValidatorInterface $validator,
    )
    {
        parent::__construct($this->validator);
    }

    #[Route(path: '/gallery/{id}', name: 'gallery:update', methods: ['PUT'])]
    public function __invoke(Request $request): JsonResponse
    {
        $gallery = $this->repository->findOneById((int) $request->attributes->get('id'));
        $dto = UpdateGalleryDto::fromRequest($request);
        $this->ensureDtoValidity($dto);

        return new JsonResponse($this->action->execute($gallery, $dto));
    }
}
