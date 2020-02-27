<?php declare(strict_types=1);

namespace App\Validator;

final class ValidScope
{
    private array $parameters;

    /**
     * ValidScope constructor.
     * @param array $parameters
     *
     */
    public function __construct(array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array
     */
    public function parameters(): array
    {
        return $this->parameters;
    }
}