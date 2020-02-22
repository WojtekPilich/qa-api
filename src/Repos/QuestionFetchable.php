<?php declare(strict_types=1);

namespace App\Repos;

use App\ValueObjects\QuestionValueObject;

interface QuestionFetchable
{
    public function getQuestion(int $id): QuestionValueObject;
}