<?php declare(strict_types=1);

namespace App\ValueObjects;

final class QuestionsValueObject
{
    /**
     * @var array $questions
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
    public function questions(): array
    {
        return $this->questions;
    }
}