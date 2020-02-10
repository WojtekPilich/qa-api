<?php

namespace App\Mapper;

use App\DTO\QuestionsDTO;
use Symfony\Component\HttpFoundation\JsonResponse;

interface Mappable
{
    public function map(QuestionsDTO $questionsDTO): JsonResponse;
}