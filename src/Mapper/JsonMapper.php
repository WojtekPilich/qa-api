<?php declare(strict_types=1);

namespace App\Mapper;

use App\ValueObjects\QuestionsValueObject;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JsonMapper implements Mappable, Defective
{
    /**
     * @param QuestionsValueObject | iterable $data
     * @return JsonResponse
     */
    public function map($data): JsonResponse
    {
        return new JsonResponse(is_object($data) ? $data->questions() : $data, Response::HTTP_OK);
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