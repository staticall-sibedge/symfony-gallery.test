<?php declare(strict_types=1);

namespace AppTests\Unit\Module\File\Dto\Store;

use App\Module\File\Dto\Action\Store\UploadFileDto as TestableDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;

final class UploadFileDtoTest extends TestCase
{
    public function testRequestWithValue(): void
    {
        $uploadedFile = new UploadedFile(__DIR__ . '/UploadFileDto/empty-file', 'test.png', null, null, true);

        $request = Request::createFromGlobals();
        $request->initialize(
            files: [
                TestableDto::REQUEST_KEY_FILE => $uploadedFile,
            ],
        );

        $dto = TestableDto::fromRequest($request);

        self::assertSame([TestableDto::KEY_UPLOADED_FILE => $uploadedFile], $dto->toArray());
    }
}
