<?php declare(strict_types=1);

namespace App\Validator;

interface Verifiable
{
    public function validate($data);
}