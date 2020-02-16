<?php

namespace App\Manager;

use App\Storage\QuestionsRequestStorage;
use App\ValueObjects\QuestionsValueObject;

interface Manageable
{
    public function prepareResponseFor(QuestionsRequestStorage $storage): QuestionsValueObject;
}