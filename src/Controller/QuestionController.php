<?php

namespace App\Controller;

use App\Message\Command\AddAnswer;
use App\Message\Query\GetQuestion;
use App\Message\Query\GetQuestions;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Question controller.
 * @Route("/",name="api_")
 */
class QuestionController extends AbstractFOSRestController
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
     * Lists all Questions
     * @Rest\Get("/questions", name="questions_get")
     * @QueryParam(map=true, name="scope", nullable=true, requirements="author|answers", description="author or answers")
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getQuestionsAction(ParamFetcher $paramFetcher): Response
    {
        $envelope = $this->messageBus->dispatch(new GetQuestions($paramFetcher->get('scope')));
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
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
