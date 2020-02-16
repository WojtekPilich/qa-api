<?php

namespace App\Scope;

final class Scope
{
    /**
     * @var array|null
     */
    private $parameters;

    /**
     * Scope constructor.
     * @param array|null $parameters
     */
    public function __construct(?array $parameters)
    {
        $this->parameters = $parameters;
    }

    /**
     * @return array|null
     */
    public function parameters()
    {
        return $this->parameters;
    }

    /**
     * @return bool
     */
    public function hasParameters()
    {
        return $this->parameters() !== null;
    }
}