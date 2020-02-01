<?php


namespace App\Manager;

use App\Message\Query\GetQuestions;
use App\Repository\QuestionRepository;
use App\Storage\QuestionsRequestStorage;
use Symfony\Component\Messenger\MessageBusInterface;

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
     * @throws \Exception
     */
    public function prepareResponse(QuestionsRequestStorage $storage)
    {
        $message = new GetQuestions($storage->getData());
        $this->messageBus->dispatch($message);
    }
}