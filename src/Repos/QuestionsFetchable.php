<?php

namespace App\Repos;

interface QuestionsFetchable
{
    public function getQuestions(): iterable;
}