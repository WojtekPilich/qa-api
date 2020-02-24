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
     * Fetch questions data from repo
     * @param Questions $questions
     * @param QuestionsRepo $questionsRepo
     * @return QuestionsValueObject
     * @throws Exception
     */
    public function __invoke(Questions $questions, QuestionsRepo $questionsRepo): QuestionsValueObject
    {
        $transformer = new ResponseTransformer($questionsRepo->getQuestions());
        return $transformer->transformQuestions($questions->scope());
    }
}