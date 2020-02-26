<?php declare(strict_types=1);

namespace App\Message\Query;

use App\Validator\ValidScope;

final class Questions
{
    private ?ValidScope $scope;

    /**
     * GetQuestions constructor.
     * @param ValidScope|null $scope
     */
    public function __construct($scope)
    {
        $this->scope = $scope;
    }

    /**
     * @return ValidScope|null
     */
    public function scope()
    {
        return $this->scope;
    }
}