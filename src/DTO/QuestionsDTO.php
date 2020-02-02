<?php

namespace App\DTO;

final class QuestionsDTO
{
    /**
     * @var array
     */
    private $questions = [];

    /**
     * QuestionsDTO constructor.
     * @param array $questions
     */
    public function __construct(array $questions)
    {
        $this->questions = $questions;
    }

    /**
     * @return array
     */
    public function getQuestions(): array
    {
        return $this->getQuestions();
    }
}