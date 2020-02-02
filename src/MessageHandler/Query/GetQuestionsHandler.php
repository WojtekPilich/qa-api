<?php

namespace App\MessageHandler\Query;

use App\DTO\QuestionsDTO;
use App\Manager\QuestionsManager;
use App\Message\Query\GetQuestions;
use App\Transformer\ResponseTransformer;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetQuestionsHandler implements MessageHandlerInterface
{
    /**
     * @var QuestionsManager
     */
    private $questionsManager;

    /**
     * QuestionController constructor.
     * @param QuestionsManager $questionsManager
     */
    public function __construct(QuestionsManager $questionsManager)
    {
        $this->questionsManager = $questionsManager;
    }

    /**
     * Triggers getAllQuestionsQuery to get question data
     * @param GetQuestions $getQuestions
     * @return QuestionsDTO
     */
    public function __invoke(GetQuestions $getQuestions): QuestionsDTO
    {
        $transformer = new ResponseTransformer($this->questionsManager->getQuestions());
        return $transformer->transform($getQuestions->getScope());
    }
}