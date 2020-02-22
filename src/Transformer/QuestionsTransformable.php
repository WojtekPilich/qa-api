<?php declare(strict_types=1);

namespace App\Transformer;

use App\Validator\ValidScope;
use App\ValueObjects\QuestionsValueObject;

interface QuestionsTransformable
{
    public function transformQuestions(?ValidScope $scope): QuestionsValueObject;
}