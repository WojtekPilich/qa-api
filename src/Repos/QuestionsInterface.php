<?php

namespace App\Repos;

interface QuestionsInterface
{
    public function getQuestions(): iterable;
}