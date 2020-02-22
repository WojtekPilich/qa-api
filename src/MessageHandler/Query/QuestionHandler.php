<?php

namespace App\MessageHandler\Query;

use App\Message\Query\GetQuestion;
use App\Repos\QuestionRepo;
use App\Transformer\ResponseTransformer;
use App\ValueObjects\QuestionValueObject;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class QuestionHandler implements MessageHandlerInterface
{
    /**
     * @var QuestionRepo
     */
    private $questionRepo;

    /**
     * QuestionController constructor.
     * @param QuestionRepo $questionRepo
     */
    public function __construct(QuestionRepo $questionRepo)
    {
        $this->questionRepo = $questionRepo;
    }

    /**
     * Triggers getQuestionQuery method to get question details
     * @param GetQuestion $getQuestion
     * @return iterable
     */
    public function __invoke(GetQuestion $getQuestion): iterable
    {
        $transformer = new ResponseTransformer($this->questionRepo->getQuestion($getQuestion->id()));
        return $transformer->transformQuestion();
    }
}