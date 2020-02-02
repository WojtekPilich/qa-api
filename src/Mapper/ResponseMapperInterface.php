<?php

namespace App\Mapper;

use App\DTO\QuestionsDTO;
use Symfony\Component\HttpFoundation\JsonResponse;

interface ResponseMapperInterface
{
    public function mapResponseToJson(QuestionsDTO $questionsDTO): JsonResponse;
}