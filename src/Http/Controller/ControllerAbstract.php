<?php declare(strict_types=1);

namespace App\Http\Controller;

use App\Module\Dto\Action\ActionDtoInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\Exception\ValidationFailedException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

abstract class ControllerAbstract extends AbstractController
{
    public function __construct(
        private readonly ValidatorInterface $validator,
    )
    {
    }

    protected function ensureDtoValidity(ActionDtoInterface $dto): void
    {
        $violations = $this->validator->validate($dto);

        if (count($violations) > 0) {
            throw new ValidationFailedException($dto, $violations);
        }
    }
}
