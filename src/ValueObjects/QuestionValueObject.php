<?php

namespace App\ValueObjects;

final class QuestionValueObject
{
    private $id;

    private $content;

    private $createdAt;

    private $questioner;

    private $answers;

    public function __construct(int $id, string $content, \DateTimeInterface $createdAt, array $questioner, ?array $answers)
    {
        $this->id = $id;
        $this->content = $content;
        $this->createdAt = $createdAt;
        $this->questioner = $questioner;
        $this->answers = $answers;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function content(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTimeInterface
     */
    public function createdAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    /**
     * @return array|null
     */
    public function questioner(): ?array
    {
        return $this->questioner;
    }

    /**
     * @return array|null
     */
    public function answers(): ?array
    {
        return $this->answers;
    }

    /**
     * @return array
     */
    public function data(): array
    {
        return (array)$this;
    }
}