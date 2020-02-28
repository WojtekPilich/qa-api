<?php declare(strict_types=1);

namespace App\ValueObjects;

final class AnswerValueObject
{
    private ?string $contents;

    /**
     * AnswerValueObject constructor.
     * @param string $contents
     */
    public function __construct(?string $contents)
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
    public function isNotProvided(): bool
    {
        return $this->contents() === null;
    }

    /**
     * @return bool
     */
    public function hasEmptyContents(): bool
    {
        return $this->contents() === "";
    }
}