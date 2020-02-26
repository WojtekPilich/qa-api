<?php declare(strict_types=1);

namespace App\ValueObjects;

final class AnswerValueObject
{
    private string $contents;

    /**
     * AnswerValueObject constructor.
     * @param string $contents
     */
    public function __construct(string $contents)
    {
        $this->contents = $contents;
    }

    /**
     * @return string
     */
    public function contents(): string
    {
        return $this->contents();
    }
}