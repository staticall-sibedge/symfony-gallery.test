<?php declare(strict_types=1);

namespace App\Http\Controller\Gallery;

use App\Http\Controller\ControllerAbstract;
use App\Module\Gallery\Action\Query\SingleGalleryAction;
use App\Module\Gallery\Action\Query\SingleGalleryActionInterface;
use App\Module\Gallery\Dto\Action\Query\SingleGalleryDto;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class SingleAction extends ControllerAbstract
{
    public function __construct(
        #[Autowire(service: SingleGalleryAction::class)]
        private readonly SingleGalleryActionInterface $action,
        private readonly ValidatorInterface $validator,
    )
    {
        parent::__construct($this->validator);
    }

    #[Route(path: '/gallery/{id}', name: 'gallery:single', methods: ['GET'])]
    public function __invoke(Request $request): JsonResponse
    {
        $dto = SingleGalleryDto::fromRequest($request);
        $this->ensureDtoValidity($dto);

        return new JsonResponse($this->action->execute($dto));
    }
}
