<?php

namespace App\Mapper;

use App\DTO\QuestionsDTO;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseMapper implements ResponseMapperInterface
{
    /**
     * @param QuestionsDTO $questionsDTO
     * @return JsonResponse
     */
    public function mapResponseToJson(QuestionsDTO $questionsDTO): JsonResponse
    {
        $resultArray = $questionsDTO->getQuestions();
        return new JsonResponse($resultArray, Response::HTTP_OK);
    }

    /**
     * @param \Exception $exception
     * @return JsonResponse
     */
    public function mapExceptionToJson(\Exception $exception)
    {
        return new JsonResponse([
            'status' => Response::$statusTexts[$exception->getCode()],
            'details' => $exception->getMessage(),
        ], $exception->getCode());
    }
}