<?php declare(strict_types=1);

namespace App\Http\Controller\Gallery;

use App\Http\Controller\ControllerAbstract;
use App\Module\Gallery\Action\Query\ListGalleriesAction;
use App\Module\Gallery\Action\Query\ListGalleriesActionInterface;
use App\Module\Gallery\Dto\Action\Query\ListGalleriesFilterDto;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class ListAction extends ControllerAbstract
{
    public function __construct(
        #[Autowire(service: ListGalleriesAction::class)]
        private readonly ListGalleriesActionInterface $action,
        private readonly ValidatorInterface $validator,
    )
    {
        parent::__construct($this->validator);
    }

    #[Route(path: '/gallery', name: 'gallery:list', methods: ['GET'])]
    public function __invoke(#[MapRequestPayload] ListGalleriesFilterDto $dto): JsonResponse
    {
        $this->ensureDtoValidity($dto);

        return new JsonResponse($this->action->execute($dto));
    }
}
