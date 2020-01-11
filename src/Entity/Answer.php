<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AnswerRepository")
 */
class Answer
{
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

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getAnswerer(): ?Answerer
    {
        return $this->Answerer;
    }

    public function setAnswerer(?Answerer $Answerer): self
    {
        $this->Answerer = $Answerer;

        return $this;
    }
}
