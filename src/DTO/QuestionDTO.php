<?php

namespace App\DTO;

final class QuestionDTO
{
    private $id;

    private $content;

    private $createdAt;

    private $questioner = [];

    private $answers = [];

    public function __construct(int $id, string $content, \DateTime $createdAt, ?array $questioner, ?array $answers)
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
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt(): \DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return array|null
     */
    public function getQuestioner(): ?array
    {
        return $this->questioner;
    }

    /**
     * @return array|null
     */
    public function getAnswers(): ?array
    {
        return $this->answers;
    }
}