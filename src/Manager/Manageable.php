<?php

namespace App\Manager;

use App\Storage\QuestionsRequestStorage;

interface Manageable
{
    public function prepare(QuestionsRequestStorage $storage);
}