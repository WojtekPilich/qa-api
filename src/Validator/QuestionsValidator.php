<?php

namespace App\Validator;

use WrongQueryParameter;

class QuestionsValidator implements ValidatorInterface
{
    /**
     * @param array $scope
     * @return ValidScope
     * @throws WrongQueryParameter
     */
    public function validate(array $scope): ValidScope
    {
        foreach ($scope as $key) {
            if ($key !== 'author' && $key !== 'answers') {
                throw WrongQueryParameter::with($key);
            }
        }

        return new ValidScope($scope);
    }
}