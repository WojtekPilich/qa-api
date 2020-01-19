<?php

namespace App\MessageHandler\Command;

use App\Entity\Answer;
use App\Entity\Answerer;
use App\Entity\Question;
use App\Message\Command\AddAnswer;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddAnswerHandler implements MessageHandlerInterface
{
    /** @var QuestionRepository $repository */
    private $repository;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    public function __construct(QuestionRepository $repository, EntityManagerInterface $entityManager)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
    }

    /**
     * Triggers getAllQuestionsQuery to get question data
     * @param AddAnswer $addAnswer
     * @return JsonResponse
     */
    public function __invoke(AddAnswer $addAnswer): JsonResponse
    {
        return $this->addAnswerCommand($addAnswer->getRequest(), $addAnswer->getId());
    }

    /**
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    private function addAnswerCommand(Request $request, $id): JsonResponse
    {
        $question = $this->repository->findOneBy(['id' => $id]);

        $answerParam = $request->get('answer');
        $nickParam = $request->get('nick');

        if (! ($question instanceof Question)) {
            return new JsonResponse([
                'Status' => 'Not found',
                'Details' => 'Question does not exist!'],
                Response::HTTP_NOT_FOUND);
        }

        if (empty($answerParam)) {
            return new JsonResponse([
                'Status' => 'Bad request',
                'Details' => 'Answer parameter is required.'],
                Response::HTTP_BAD_REQUEST);
        }

        if (! ValidationHelper::checkParamLength($answerParam) || ! ValidationHelper::checkParamLength($nickParam) ) {
            return new JsonResponse([
                'Status' => 'Bad request',
                'Details' => 'Request content is too long.'],
                Response::HTTP_BAD_REQUEST);
        }

        if (! ValidationHelper::checkForbiddenWords($answerParam) || ! ValidationHelper::checkForbiddenWords($nickParam)) {
            return new JsonResponse([
                'Status' => 'Bad request',
                'Details' => 'Request body contains forbidden words.'],
                Response::HTTP_BAD_REQUEST);
        }

        $this->saveAnswerData($request, $question);

        return new JsonResponse([
            'Status' => 'Created',
            'Details' => "Successfully added new answer. Question details available here: http://127.0.0.1:8000/questions/{$id}"],
            Response::HTTP_CREATED);
    }

    /**
     * Save answer data fetched from post controller method.
     * @param Request $request
     * @param Question $question
     */
    protected function saveAnswerData(Request $request, Question $question): void
    {
        $answerer = (new Answerer())
            ->setNick($request->get('nick') ?? 'Anonymus');
        $this->entityManager->persist($answerer);

        $answer = (new Answer())
            ->setContent($request->get('answer'))
            ->setAnswerer($answerer)
            ->setQuestion($question);

        $question->addAnswer($answer);

        $this->entityManager->persist($answer);
        $this->entityManager->flush();
    }
}