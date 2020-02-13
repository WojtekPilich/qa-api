<?php

namespace App\MessageHandler\Query;

use App\ValueObjects\QuestionsValueObject;
use App\Manager\QuestionsManager;
use App\Message\Query\GetQuestions;
use App\Transformer\ResponseTransformer;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class QuestionsHandler implements MessageHandlerInterface
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
     * @param GetQuestions $questions
     * @return QuestionsValueObject
     * @throws Exception
     */
    public function __invoke(GetQuestions $questions): QuestionsValueObject
    {
        $transformer = new ResponseTransformer($this->questionsManager->getQuestions());
        return $transformer->transform($questions->scope());
    }
}