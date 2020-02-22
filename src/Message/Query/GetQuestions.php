<?php declare(strict_types=1);

namespace App\Message\Query;

final class GetQuestions
{
    private $scope;

    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    public function scope()
    {
        return $this->scope;
    }
}