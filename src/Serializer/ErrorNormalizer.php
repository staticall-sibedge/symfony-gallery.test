<?php declare(strict_types=1);

namespace App\Serializer;

use Symfony\Component\ErrorHandler\Exception\FlattenException;
use Symfony\Component\Serializer\Normalizer\ProblemNormalizer;

final class ErrorNormalizer extends ProblemNormalizer
{
    public const string KEY_STATUS = 'status';
    public const string KEY_DATA = 'data';
    public const string KEY_ERROR = 'error';
    public const string KEY_ERROR_MESSAGE = 'message';
    public const string KEY_ERROR_CODE = 'code';

    /**
     * @param FlattenException $exception
     */
    public function normalize(mixed $exception, ?string $format = null, array $context = []): array
    {
        return [
            self::KEY_STATUS => 'error',
            self::KEY_DATA => [],
            self::KEY_ERROR => [
                self::KEY_ERROR_MESSAGE => $exception->getMessage(),
                self::KEY_ERROR_CODE => $exception->getStatusCode(),
            ],
        ];
    }

    public function getSupportedTypes(?string $format): array
    {
        return [
            FlattenException::class => true,
        ];
    }
}
