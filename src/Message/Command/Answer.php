<?php declare(strict_types=1);

namespace App\Message\Command;

final class Answer
{
    private string $answer;

    private ?string $nick;

    private int $questionId;

    public function __construct(string $answer, ?string $nick, int $questionId)
    {
        $this->answer = $answer;
        $this->nick = $nick;
        $this->questionId = $questionId;
    }

    /**
     * @return string
     */
    public function answer(): string
    {
        return $this->answer;
    }

    /**
     * @return string|null
     */
    public function nick(): ?string
    {
        return $this->nick;
    }

    /**
     * @return int
     */
    public function questionId(): int
    {
        return $this->questionId;
    }
}