<?php

namespace App\MessageHandler;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questioner;
use App\Message\GetQuestions;
use App\Repository\QuestionRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class GetQuestionsHandler implements MessageHandlerInterface
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
     * Triggers getAllQuestionsInJson method that handles creating response
     * @param GetQuestions $getQuestions
     * @return JsonResponse
     */
    public function __invoke(GetQuestions $getQuestions): JsonResponse
    {
        return $this->getQuestionsInJson($getQuestions->getScope());
    }

    /**
     * Returns all questions json for given scope or error response
     * @param $scope
     * @return JsonResponse
     */
    protected function getQuestionsInJson($scope)
    {
        $questions = $this->repository->findAll();

        if ($scope === '') {
            return new JsonResponse([
                'status' => 'bad request',
                'details' => 'wrong query parameters.'
            ],Response::HTTP_BAD_REQUEST);
        }

        if (empty($questions)) {
            return new JsonResponse([
                'status' => 'not found',
                'details' => 'no questions to show.'
            ],Response::HTTP_NOT_FOUND);
        }

        $data = [
            'status' => 'ok',
        ];
        $answersData = [];
        $questionerData = [];

        /** @var Question $question */
        foreach ($questions as $question) {

            /** @var Answer[] $answers */
            $answers = $question->getAnswers();
            foreach ($answers as $answer) {
                $answersData[] = [
                    'id' => $answer->getId(),
                    'content' => $answer->getContent(),
                    'created_at' => $answer->getCreatedAt()->format('Y-m-d H:i:s'),
                    'answerer_nick' => $answer->getAnswerer()->getNick(),
                ];
            }

            /** @var Questioner $author */
            $author = $question->getQuestioner();
            $questionerData = [
                'email' => $author->getEmail(),
                'name' => $author->getName(),
                'nick' => $author->getNick(),
            ];

            $data['questions'][] = [
                'id' => $question->getId(),
                'content' => $question->getContent(),
                'created_at' => $question->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        foreach ($data['questions'] as $key => &$value) {
            if ($scope && in_array('author', $scope)) {
                $value['questioner'] = $questionerData;
            }
            if ($scope && in_array('answers', $scope)) {
                $value['answers'] = $answersData;
            }
        }

        return new JsonResponse($data, Response::HTTP_OK);
    }
}