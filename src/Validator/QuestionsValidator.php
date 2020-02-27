<?php declare(strict_types=1);

namespace App\Validator;

use App\Scope\Scope;
use WrongQueryParameter;

final class QuestionsValidator implements Verifiable
{
    /**
     * @param Scope $scope
     * @return ValidScope
     * @throws WrongQueryParameter
     */
    public function validate($scope): ValidScope
    {
        foreach ($scope->parameters() as $parameter) {
            if ($parameter !== 'author' && $parameter !== 'answers') {
                throw WrongQueryParameter::whichIs($parameter);
            }
        }

        return new ValidScope($scope->parameters());
    }
}