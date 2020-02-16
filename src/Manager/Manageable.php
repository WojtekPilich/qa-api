<?php

namespace App\Manager;

use App\Validator\ValidScope;
use App\ValueObjects\QuestionsValueObject;

interface Manageable
{
    public function prepareResponseFor(?ValidScope $storage): QuestionsValueObject;
}