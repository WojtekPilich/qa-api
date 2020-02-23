<?php declare(strict_types=1);

namespace App\Transformer;

interface QuestionTransformable
{
    public function transformQuestion(): iterable;
}