<?php

namespace App\Controller;

use App\Manager\QuestionsManager;
use App\Message\Query\GetQuestion;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class QuestionController extends AbstractFOSRestController
{

    /** @var MessageBusInterface $messageBus */
    private $messageBus;
    /**
     * @var QuestionsManager
     */
    private $manager;

    /**
     * QuestionController constructor.
     * @param MessageBusInterface $messageBus
     * @param QuestionsManager $manager
     */
    public function __construct(MessageBusInterface $messageBus, QuestionsManager $manager)
    {
        $this->messageBus = $messageBus;
        $this->manager = $manager;
    }
    /**
     * Gets one question
     * @Rest\Get("/questions/{id}", name="question_get")
     * @param int $id
     * @return Response
     */
    public function getQuestionAction(int $id): Response
    {
        $envelope = $this->messageBus->dispatch(new GetQuestion($id));
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }
}
