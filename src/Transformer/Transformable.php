<?php

namespace App\Transformer;

use App\Validator\ValidScope;

interface Transformable
{
    public function transform(?ValidScope $scope);
}