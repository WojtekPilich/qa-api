<?php

namespace App\MessageHandler\Query;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questioner;
use App\Message\Query\GetQuestion;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetQuestionHandler implements MessageHandlerInterface
{
    /** @var QuestionRepository $repository */
    private $repository;

    /**
     * QuestionController constructor.
     * @param QuestionRepository $repository
     */
    public function __construct(QuestionRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Triggers getQuestionQuery method to get question details
     * @param GetQuestion $getQuestion
     * @return JsonResponse
     */
    public function __invoke(GetQuestion $getQuestion): JsonResponse
    {
        return $this->getQuestionQuery($getQuestion->getId());
    }

    /**
     * Returns question data from database
     * @param $questionId
     * @return JsonResponse
     */
    private function getQuestionQuery($questionId): JsonResponse
    {
        $question = $this->repository->findOneBy(['id' => $questionId]);

        if (! ($question instanceof Question)) {
            return new JsonResponse([
                'status' => 'not found',
                'details' => 'question does not exist!'],
                Response::HTTP_NOT_FOUND);
        }

        /** @var Questioner $author */
        $author = $question->getQuestioner();

        /** @var Answer[] $answers */
        $answers = $question->getAnswers();

        $answersData = [];
        foreach ($answers as $answer) {
            $answersData[] = [
                'id' => $answer->getId(),
                'content' => $answer->getContent(),
                'created_at' => $answer->getCreatedAt()->format('Y-m-d H:i:s'),
                'answerer_nick' => $answer->getAnswerer()->getNick(),
            ];
        }

        $data = [
            'status' => 'ok',
            'id' => $question->getId(),
            'content' => $question->getContent(),
            'created_at' => $question->getCreatedAt()->format('Y-m-d H:i:s'),
            'questioner' => [
                'email' => $author->getEmail(),
                'name' => $author->getName(),
                'nick' => $author->getNick(),
            ],
            'answers' => $answersData,
        ];

        return new JsonResponse($data, Response::HTTP_OK);
    }
}