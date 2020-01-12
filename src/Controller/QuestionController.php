<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Entity\Answerer;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Question controller.
 * @Route("/",name="api_")
 */
class QuestionController extends AbstractFOSRestController
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
     * List all Questions
     * @Rest\Get("/questions")
     * @QueryParam( map=true, name="scope", requirements="[a-z]+", nullable=true, description="author or answers")
     * @param ParamFetcher $paramFetcher
     * @return Response
     */
    public function getAllQuestionsAction(ParamFetcher $paramFetcher): Response
    {
        $questions = $this->repository->getAllQuestionsDataWithScope($paramFetcher->get('scope'));

        if (empty($questions)) {
            return new JsonResponse("There are no questions to show. Please try out later.", Response::HTTP_NOT_FOUND);
        }

        $view = $this->view($questions, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * List all Questions
     * @Rest\Get("/question/{id}")
     * @param Request $request
     * @param null|int $id
     * @return Response
     * @throws NonUniqueResultException
     */
    public function getOneQuestionWithDetailsAction(Request $request, ?int $id): Response
    {
        $question = $this->repository->getQuestionById($id);

        if (empty($question)) {
            return new JsonResponse("Question with id: {$id} does not exist!", Response::HTTP_NOT_FOUND);
        }

        $view = $this->view($question, Response::HTTP_OK);
        return $this->handleView($view);
    }

    /**
     * Add answer to
     * @Rest\Post("/question/{id}/addAnswer")
     * @RequestParam(name="answer", nullable=false, requirements="[a-z]+", description="Answer to given question")
     * @RequestParam(name="nick", nullable=false, requirements="[a-z]+", description="Answerer nick")
     * @param Request $request
     * @param EntityManagerInterface $em
     * @param int $id
     * @return Response
     */
    public function postAddAnswerToQuestionAction(Request $request, EntityManagerInterface $em, int $id): Response
    {
        $repository = $this->getDoctrine()->getRepository(Question::class);
        $question = $repository->findOneBy(['id' => $id]);

        if (! ($question instanceof Question)) {
            return new JsonResponse("Cannot add answer to question with id: {$id} because it does not exist!", Response::HTTP_NOT_FOUND);
        }
        $answerer = (new Answerer())
            ->setNick($request->get('nick'));

        $em->persist($answerer);

        $answer = (new Answer())
            ->setContent($request->get('answer'))
            ->setAnswerer($answerer)
            ->setQuestion($question);

        $question->addAnswer($answer);

        $em->persist($answer);
        $em->flush();

        return new JsonResponse("Successfully added new answer to question with id: $id", Response::HTTP_CREATED);
    }
}
