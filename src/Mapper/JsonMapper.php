<?php

namespace App\Mapper;

use App\DTO\QuestionsDTO;
use Exception;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class JsonMapper implements Mappable, Catchable
{
    /**
     * @param QuestionsDTO $questionsDTO
     * @return JsonResponse
     */
    public function map(QuestionsDTO $questionsDTO): JsonResponse
    {
        $resultArray = $questionsDTO->getQuestions();
        return new JsonResponse($resultArray, Response::HTTP_OK);
    }

    /**
     * @param Exception $exception
     * @return JsonResponse
     */
    public function catch(Exception $exception): JsonResponse
    {
        return new JsonResponse([
            'status' => Response::$statusTexts[$exception->getCode()],
            'details' => $exception->getMessage(),
        ], $exception->getCode());
    }
}