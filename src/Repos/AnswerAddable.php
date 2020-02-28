<?php declare(strict_types=1);

namespace App\Repos;

use App\Message\Command\Answer;

interface AnswerAddable
{
    public function addAnswer(Answer $answer);
}