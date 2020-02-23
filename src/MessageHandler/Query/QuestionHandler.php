<?php declare(strict_types=1);

namespace App\MessageHandler\Query;

use App\Exception\NotFound;
use App\Message\Query\GetQuestion;
use App\Repos\QuestionRepo;
use App\Transformer\ResponseTransformer;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class QuestionHandler implements MessageHandlerInterface
{
    /**
     * @var QuestionRepo $questionRepo
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
     * @throws NotFound
     */
    public function __invoke(GetQuestion $getQuestion): iterable
    {
        $transformer = new ResponseTransformer($this->questionRepo->getQuestion($getQuestion->id()));
        return $transformer->transformQuestion();
    }
}