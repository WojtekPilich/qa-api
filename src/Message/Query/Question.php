<?php declare(strict_types=1);

namespace App\Message\Query;

final class Question
{
    private int $id;

    /**
     * GetQuestion constructor.
     * @param int $id
     */
    public function __construct(int $id)
    {
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }
}