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
     * @return string|null
     */
    public function contents()
    {
        return $this->contents;
    }

    /**
     * @return bool
     */
    public function hasContents(): bool
    {
        return $this->contents() !== "";
    }
}