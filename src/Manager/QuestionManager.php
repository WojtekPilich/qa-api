<?php declare(strict_types=1);

namespace App\Manager;

use App\Message\Query\Question;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QuestionManager implements QuestionManageable
{
    /**
     * @var MessageBusInterface
     */
    private $messageBus;

    /**
     * QuestionsManager constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @param int $id
     * @return iterable
     */
    public function prepareResponseFor(int $id): iterable
    {
        $message = new Question($id);
        $envelope = $this->messageBus->dispatch($message);

        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }
}