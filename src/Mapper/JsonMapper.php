<?php declare(strict_types=1);

namespace App\Mapper;

use App\ValueObjects\QuestionsValueObject;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JsonMapper implements Mappable, Defective
{
    /**
     * @param QuestionsValueObject $questionsValueObject
     * @return JsonResponse
     */
    public function map(QuestionsValueObject $questionsValueObject): JsonResponse
    {
        return new JsonResponse($questionsValueObject->questions(), Response::HTTP_OK);
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