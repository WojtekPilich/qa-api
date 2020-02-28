<?php declare(strict_types=1);

namespace App\Validator;

final class ValidAnswer
{
    private string $contents;

    /**
     * ValidAnswer constructor.
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
        return $this->contents;
    }
}