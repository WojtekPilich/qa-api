<?php declare(strict_types=1);

namespace App\MessageHandler\Command;

use App\Mapper\JsonMapper;
use App\Message\Command\Answer;
use App\Repos\AnswerRepo;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class AnswerHandler implements MessageHandlerInterface
{
    private AnswerRepo $repo;

    private JsonMapper $mapper;

    /**
     * AddAnswerHandler constructor.
     * @param AnswerRepo $repo
     * @param JsonMapper $mapper
     */
    public function __construct(AnswerRepo $repo, JsonMapper $mapper)
    {
        $this->repo = $repo;
        $this->mapper = $mapper;
    }

    /**
     * @param Answer $answer
     * @return JsonResponse
     */
    public function __invoke(Answer $answer): JsonResponse
    {
        $this->repo->addAnswer($answer);

        return $this->mapper->map([
            'status' => 'created',
            'details' => "successfully added new answer, question details available here: http://127.0.0.1:8000/questions/{$answer->questionId()}"
        ], Response::HTTP_CREATED);
    }
}