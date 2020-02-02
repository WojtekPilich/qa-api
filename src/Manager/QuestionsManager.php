<?php

namespace App\Manager;

use App\DTO\QuestionDTO;
use App\DTO\QuestionsDTO;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questioner;
use App\Message\Query\GetQuestions;
use App\Repository\QuestionRepository;
use App\Storage\QuestionsRequestStorage;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class QuestionsManager implements ManagerInterface
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;
    /**
     * @var QuestionRepository
     */
    private $questionRepository;

    /**
     * QuestionsManager constructor.
     * @param MessageBusInterface $messageBus
     * @param QuestionRepository $questionRepository
     */
    public function __construct(MessageBusInterface $messageBus, QuestionRepository $questionRepository)
    {
        $this->messageBus = $messageBus;
        $this->questionRepository = $questionRepository;
    }

    /**
     * @param QuestionsRequestStorage $storage
     * @return QuestionsDTO
     */
    public function prepareResult(QuestionsRequestStorage $storage): QuestionsDTO
    {
        $message = new GetQuestions($storage->getData());
        $envelope = $this->messageBus->dispatch($message);

        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getQuestions(): array
    {
        $questions = $this->questionRepository->findAll();

        $data = [];
        $answersData = [];

        if (empty($questions)) {
            throw new \Exception('No questions found!');
        }

        /** @var Question $question */
        foreach ($questions as $question) {

            /** @var Answer[] $answers */
            $answers = $question->getAnswers();
            foreach ($answers as $answer) {
                $answersData[] = [
                    'id' => $answer->getId(),
                    'content' => $answer->getContent(),
                    'created_at' => $answer->getCreatedAt()->format('Y-m-d H:i:s'),
                    'answerer_nick' => $answer->getAnswerer()->getNick(),
                ];
            }

            /** @var Questioner $author */
            $author = $question->getQuestioner();
            $questionerData = [
                'email' => $author->getEmail(),
                'name' => $author->getName(),
                'nick' => $author->getNick(),
            ];

            $dto = new QuestionDTO(
                $question->getId(),
                $question->getContent(),
                $question->getCreatedAt(),
                $questionerData,
                $answersData
            );

            $data[]= $dto;
        }

        return $data;
    }
}