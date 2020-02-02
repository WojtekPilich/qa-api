<?php

namespace App\Transformer;

use App\Validator\ValidScope;

interface ResponseTransformerInterface
{
    public function transform(?ValidScope $scope);
}