<?php declare(strict_types=1);

namespace App\Manager;

use App\Message\Query\Questions;
use App\Validator\ValidScope;
use App\ValueObjects\QuestionsValueObject;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

final class QuestionsManager implements QuestionsManageable
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

    /**
     * @param ValidScope|null $validScope
     * @return QuestionsValueObject
     */
    public function prepareResponseFor(?ValidScope $validScope): QuestionsValueObject
    {
        $message = new Questions($validScope);
        $envelope = $this->messageBus->dispatch($message);

        $handledStamp = $envelope->last(HandledStamp::class);
        return $handledStamp->getResult();
    }
}