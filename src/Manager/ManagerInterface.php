<?php

namespace App\Manager;

use App\Storage\QuestionsRequestStorage;

interface ManagerInterface
{
    public function prepareResult(QuestionsRequestStorage $storage);
}