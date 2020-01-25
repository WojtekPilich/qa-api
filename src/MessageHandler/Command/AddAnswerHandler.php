<?php

namespace App\MessageHandler\Command;

use App\Entity\Answer;
use App\Entity\Answerer;
use App\Entity\Question;
use App\Message\Command\AddAnswer;
use App\MessageHandler\Helpers\AnswerValidator;
use App\Repository\ForbiddenWordRepository;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class AddAnswerHandler implements MessageHandlerInterface
{
    /** @var QuestionRepository $questionRepo */
    private $questionRepo;

    /** @var ForbiddenWordRepository $forbiddenRepo */
    private $forbiddenRepo;

    /** @var EntityManagerInterface $entityManager */
    private $entityManager;

    /** @var AnswerValidator $validator */
    private $validator;

    /**
     * AddAnswerHandler constructor.
     * @param QuestionRepository $questionRepo
     * @param ForbiddenWordRepository $forbiddenRepo
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(QuestionRepository $questionRepo, ForbiddenWordRepository $forbiddenRepo, EntityManagerInterface $entityManager)
    {
        $this->questionRepo = $questionRepo;
        $this->forbiddenRepo = $forbiddenRepo;
        $this->entityManager = $entityManager;
        $this->validator = new AnswerValidator($this->forbiddenRepo->findAll());
    }

    /**
     * Triggers addAnswerCommand to save new answer
     * @param AddAnswer $addAnswer
     * @return JsonResponse
     */
    public function __invoke(AddAnswer $addAnswer): JsonResponse
    {
        return $this->addAnswerCommand($addAnswer->getRequest(), $addAnswer->getId());
    }

    /**
     * Handles answer creation
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    private function addAnswerCommand(Request $request, $id): JsonResponse
    {
        $question = $this->questionRepo->findOneBy(['id' => $id]);

        $answerParam = $request->get('answer');
        $nickParam = $request->get('nick');

        if (! ($question instanceof Question)) {
            return new JsonResponse([
                'status' => 'not found',
                'details' => 'question does not exist!'],
                Response::HTTP_NOT_FOUND);
        }

        if (empty($answerParam)) {
            return new JsonResponse([
                'status' => 'bad request',
                'details' => 'answer parameter is required.'],
                Response::HTTP_BAD_REQUEST);
        }

        // validate params length
        if (! $this->validator->checkLength($answerParam) || ! $this->validator->checkLength($nickParam)) {
            return new JsonResponse([
                'status' => 'bad request',
                'details' => 'request content is too long.'],
                Response::HTTP_BAD_REQUEST);
        }

        // validate forbidden words
        if (! $this->validator->searchForbiddenWords($answerParam) || ! $this->validator->searchForbiddenWords($nickParam)) {
            return new JsonResponse([
                'status' => 'bad request',
                'details' => 'request body contains forbidden words.'],
                Response::HTTP_BAD_REQUEST);
        }

        $this->saveAnswerData($request, $question);

        return new JsonResponse([
            'status' => 'created',
            'details' => "successfully added new answer, question details available here: http://127.0.0.1:8000/questions/{$id}"],
            Response::HTTP_CREATED);
    }

    /**
     * Saves answer into database
     * @param Request $request
     * @param Question $question
     */
    private function saveAnswerData(Request $request, Question $question): void
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