<?php

namespace App\Validator;

use App\Scope\Scope;

interface ValidatorInterface
{
    public function validate(Scope $scope);
}