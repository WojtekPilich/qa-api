<?php declare(strict_types=1);

namespace App\Repos;

interface QuestionsFetchable
{
    public function getQuestions(): iterable;
}