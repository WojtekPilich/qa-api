<?php

namespace App\Mapper;

use App\ValueObjects\QuestionsValueObject;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

final class JsonMapper implements Mappable, Defective
{
    /**
     * @param QuestionsValueObject $questionsDTO
     * @return JsonResponse
     */
    public function map(QuestionsValueObject $questionsDTO): JsonResponse
    {
        $resultArray = $questionsDTO->getQuestions();
        return new JsonResponse($resultArray, Response::HTTP_OK);
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