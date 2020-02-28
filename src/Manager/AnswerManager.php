<?php declare(strict_types=1);

namespace App\Manager;

use App\Message\Command\Answer;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class AnswerManager implements AnswerSaveable
{
    private MessageBusInterface $messageBus;

    /**
     * QuestionsManager constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function save(Answer $answer)
    {
        $envelope = $this->messageBus->dispatch($answer);

        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }
}