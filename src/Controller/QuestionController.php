<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Answerer;
use App\Entity\Question;
use App\Message\Query\GetQuestion;
use App\Message\Query\GetQuestions;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Question controller.
 * @Route("/",name="api_")
 */
class QuestionController extends AbstractFOSRestController
{
    /** @var MessageBusInterface $messageBus */
    private $messageBus;

    /**
     * QuestionController constructor.
     * @param MessageBusInterface $messageBus
     */
    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * List all Questions
     * @Rest\Get("/questions", name="questions_get")
     * @QueryParam(map=true, name="scope", nullable=true, requirements="author|answers", description="author or answers")
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getQuestionsAction(ParamFetcher $paramFetcher): Response
    {
        $envelope = $this->messageBus->dispatch(new GetQuestions($paramFetcher->get('scope')));
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }

    /**
     * Get one question
     * @Rest\Get("/questions/{id}", name="question_get")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function getQuestionAction(Request $request, int $id): Response
    {
        $envelope = $this->messageBus->dispatch(new GetQuestion($id));
        $handledStamp = $envelope->last(HandledStamp::class);

        return $handledStamp->getResult();
    }

    /**
     * Add answer to
     * @Rest\Post("/questions/{id}/answer", name="answer_add")
     * @RequestParam(name="answer", nullable=false, description="Answer to given question")
     * @RequestParam(name="nick", nullable=true, description="Answerer nick")
     * @param Request $request
     * @param int $id
     * @return Response
     */
    public function postAnswerAction(Request $request, int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $question = $repository->findOneBy(['id' => $id]);

        $answerParam = $request->get('answer');
        $nickParam = $request->get('nick');

        if (empty($answerParam)) {
            return new JsonResponse([
                'Status' => 'Bad request',
                'Details' => 'Answer parameter is required.'],
                Response::HTTP_BAD_REQUEST);
        }

        if (strlen($answerParam) > 255 || strlen($nickParam) > 255) {
            return new JsonResponse([
                'Status' => 'Bad request',
                'Details' => 'Request content is too long.'],
                Response::HTTP_BAD_REQUEST);
        }

        foreach (Answer::$forbiddenWords as $forbiddenWord) {
            if (strpos($answerParam, $forbiddenWord) !== false || strpos($nickParam, $forbiddenWord) !== false) {
                return new JsonResponse([
                    'Status' => 'Bad request',
                    'Details' => 'Request body contains forbidden words.'],
                    Response::HTTP_BAD_REQUEST);
            }
        }

        if (! ($question instanceof Question)) {
            return new JsonResponse([
                'Status' => 'Not found',
                'Details' => 'Question does not exist!'],
                Response::HTTP_NOT_FOUND);
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
        $em = $this->getDoctrine()->getManager();

        $answerer = (new Answerer())
            ->setNick($request->get('nick') ?? 'Anonymus');
        $em->persist($answerer);

        $answer = (new Answer())
            ->setContent($request->get('answer'))
            ->setAnswerer($answerer)
            ->setQuestion($question);

        $question->addAnswer($answer);

        $em->persist($answer);
        $em->flush();
    }
}
