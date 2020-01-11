<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\QuestionRepository")
 */
class Question
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
     * @ORM\ManyToOne(targetEntity="App\Entity\Questioner", inversedBy="questions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Questioner;

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

    public function getQuestioner(): ?Questioner
    {
        return $this->Questioner;
    }

    public function setQuestioner(?Questioner $Questioner): self
    {
        $this->Questioner = $Questioner;

        return $this;
    }
}
