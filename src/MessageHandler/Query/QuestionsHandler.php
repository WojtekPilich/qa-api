<?php declare(strict_types=1);

namespace App\MessageHandler\Query;

use App\Message\Query\Questions;
use App\Repos\QuestionsRepo;
use App\Transformer\ResponseTransformer;
use App\ValueObjects\QuestionsValueObject;
use Exception;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class QuestionsHandler implements MessageHandlerInterface
{
    /**
     * @var QuestionsRepo $questionsRepo
     */
    private $questionsRepo;

    /**
     * QuestionController constructor.
     * @param QuestionsRepo $questionsRepo
     */
    public function __construct(QuestionsRepo $questionsRepo)
    {
        $this->questionsRepo = $questionsRepo;
    }

    /**
     * Triggers getAllQuestionsQuery to get question data
     * @param Questions $questions
     * @return QuestionsValueObject
     * @throws Exception
     */
    public function __invoke(Questions $questions): QuestionsValueObject
    {
        $transformer = new ResponseTransformer($this->questionsRepo->getQuestions());
        return $transformer->transformQuestions($questions->scope());
    }
}