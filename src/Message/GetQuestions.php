<?php

namespace App\Message;

final class GetQuestions
{
    private $scope;

    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    public function getScope()
    {
        return $this->scope;
    }
}