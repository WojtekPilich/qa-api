<?php

namespace App\Validator;

use Exception;

class QuestionsValidator implements ValidatorInterface
{
    /**
     * @param array $scope
     * @return ValidScope
     * @throws Exception
     */
    public function validate(array $scope): ValidScope
    {
        foreach ($scope as $key) {
            if ($key !== 'author' && $key !== 'answers') {
                throw \WrongQueryParameter::withString($key);
            }
        }

        return new ValidScope($scope);
    }
}