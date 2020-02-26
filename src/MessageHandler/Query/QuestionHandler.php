<?php declare(strict_types=1);

namespace App\MessageHandler\Query;

use App\Exception\NotFound;
use App\Message\Query\Question;
use App\Repos\QuestionRepo;
use App\Transformer\ResponseTransformer;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class QuestionHandler implements MessageHandlerInterface
{
    private QuestionRepo $questionRepo;

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
     * @param Question $question
     * @return iterable
     * @throws NotFound
     */
    public function __invoke(Question $question): iterable
    {
        $transformer = new ResponseTransformer($this->questionRepo->getQuestion($question->id()));
        return $transformer->transformQuestion();
    }
}