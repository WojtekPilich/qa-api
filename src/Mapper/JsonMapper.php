<?php declare(strict_types=1);

namespace App\Mapper;

use App\ValueObjects\QuestionsValueObject;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JsonMapper implements MapOrThrow
{
    /**
     * @param QuestionsValueObject | iterable $data
     * @param int $responseCode
     * @return JsonResponse
     */
    public function map($data, int $responseCode = Response::HTTP_OK): JsonResponse
    {
        return new JsonResponse(is_object($data) ? $data->questions() : $data, $responseCode);
    }

    /**
     * @param Exception $exception
     * @return JsonResponse
     */
    public function handle(Exception $exception): JsonResponse
    {
        return new JsonResponse([
            'status' => Response::$statusTexts[$exception->getCode()],
            'details' => $exception->getMessage(),
        ], $exception->getCode());
    }
}