<?php

namespace App\ValueObjects;

final class QuestionsValueObject
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
        return $this->questions;
    }
}