<?php

namespace App\Validator;

class ValidScope
{
    /**
     * @var array
     */
    private $scope;

    /**
     * ValidScope constructor.
     * @param array $scope
     */
    public function __construct(array $scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return array
     */
    public function getScope(): array
    {
        return $this->scope;
    }
}