<?php

namespace App\Mapper;

use App\ValueObjects\QuestionsValueObject;
use Symfony\Component\HttpFoundation\JsonResponse;

interface Mappable
{
    public function map(QuestionsValueObject $questionsDTO): JsonResponse;
}