<?php declare(strict_types=1);

namespace App\Manager;

use App\Message\Command\Answer;

interface AnswerSaveable
{
    public function save(Answer $answer);
}