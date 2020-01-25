<?php

namespace App\Controller;

use App\Message\Command\AddAnswer;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class AnswersController extends AbstractFOSRestController
{
    /** @var MessageBusInterface $messageBus */
    private $messageBus;

    /**
     * QuestionController constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * Adds answer to question for given id
     * @Rest\Post("/questions/{id}/answer", name="answer_add")
     * @RequestParam(name="answer", nullable=false, description="Answer to given question")
     * @RequestParam(name="nick", nullable=true, description="Answerer nick")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function postAnswerAction(Request $request, int $id): Response
    {
        $envelope = $this->messageBus->dispatch(new AddAnswer($request, $id));
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
