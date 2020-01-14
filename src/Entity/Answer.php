<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
    /**
     * Forbidden words in answer
     * @var array
     */
    public static $forbiddenWords = [
        'fuck',
        'motherfucker',
        'pussy',
        'shit',
        'bitch',
        'whore',
        'slut',
        'cocksucker',
        'anal',
        'asshole',
        'ass',
    ];

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $content;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Answerer", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Answerer;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Question", inversedBy="answers")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Question;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $created_at;

    public function __construct()
    {
        $this->setCreatedAt(new \DateTime());
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAnswerer(): Answerer
    {
        return $this->Answerer;
    }

    public function setAnswerer(Answerer $Answerer): self
    {
        $this->Answerer = $Answerer;

        return $this;
    }

    public function getQuestion(): Question
    {
        return $this->Question;
    }

    public function setQuestion(Question $Question): self
    {
        $this->Question = $Question;

        return $this;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
