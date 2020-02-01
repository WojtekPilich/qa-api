<?php

namespace App\Manager;

use App\Storage\QuestionsRequestStorage;

interface ManagerInterface
{
    public function prepareResponse(QuestionsRequestStorage $storage);
}