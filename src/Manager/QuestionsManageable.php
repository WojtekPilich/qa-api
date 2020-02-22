<?php declare(strict_types=1);

namespace App\Manager;

use App\Validator\ValidScope;
use App\ValueObjects\QuestionsValueObject;

interface QuestionsManageable
{
    public function prepareResponseFor(?ValidScope $cope): QuestionsValueObject;
}