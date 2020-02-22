<?php declare(strict_types=1);

namespace App\Transformer;

use App\ValueObjects\QuestionValueObject;

interface QuestionTransformable
{
    public function transformQuestion(): iterable;
}