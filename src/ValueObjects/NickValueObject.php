<?php declare(strict_types=1);

namespace App\ValueObjects;

final class NickValueObject
{
    private ?string $contents;

    /**
     * NickValueObject constructor.
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
    public function isProvided(): bool
    {
        return $this->contents() !== null;
    }

    /**
     * @return bool
     */
    public function hasEmptyContents(): bool
    {
        return $this->contents() === "";
    }
}