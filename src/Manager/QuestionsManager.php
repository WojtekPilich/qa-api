<?php

namespace App\Manager;

use App\ValueObjects\QuestionValueObject;
use App\ValueObjects\QuestionsValueObject;
use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questioner;
use App\Exception\NotFound;
use App\Message\Query\GetQuestions;
use App\Repository\QuestionRepository;
use App\Storage\QuestionsRequestStorage;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QuestionsManager implements Manageable
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
     * @return QuestionsValueObject
     */
    public function prepareResponseFor(QuestionsRequestStorage $storage): QuestionsValueObject
    {
        $message = new GetQuestions($storage->data());
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

        if (empty($questions)) {
            throw NotFound::with('No questions found!', Response::HTTP_NOT_FOUND);
        }

        /** @var Question $question */
        foreach ($questions as $question) {
        $answersData = [];

            /** @var Answer $answer */
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

            $dto = new QuestionValueObject(
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