<?php declare(strict_types=1);

namespace App\Manager;

interface QuestionManageable
{
    public function prepareResponseFor(int $id): iterable;
}