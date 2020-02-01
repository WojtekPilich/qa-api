<?php

namespace App\Controller;

use App\Manager\QuestionsManager;
use App\Message\Query\GetQuestion;
use App\Message\Query\GetQuestions;
use App\Storage\QuestionsRequestStorage;
use App\Validator\QuestionsValidator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Exception\InvalidParameterException;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Question controller.
 * @Route("/",name="api_")
 */
class QuestionsController extends AbstractFOSRestController
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
     * Lists all Questions
     * @Rest\Get("/questions", name="questions_get")
     * @QueryParam(map=true, name="scope", strict=true, nullable=true, description="author or answers")
     * @param ParamFetcher $paramFetcher
     * @param QuestionsValidator $validator
     * @return Response
     * @throws \Exception
     */
    public function getQuestionsAction(ParamFetcher $paramFetcher, QuestionsValidator $validator): Response
    {
        try {
            $validScope = $validator->validate($paramFetcher->get('scope'));
        } catch (\Exception $exception) {
            return new Response($exception->getMessage());
        }


        $this->manager->prepareResponse(new QuestionsRequestStorage($validScope));

//        $envelope = $this->messageBus->dispatch(new GetQuestions($paramFetcher->get('scope')));
//        $handledStamp = $envelope->last(HandledStamp::class);
//
//        return $handledStamp->getResult();
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
