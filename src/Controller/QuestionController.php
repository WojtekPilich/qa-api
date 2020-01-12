<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Doctrine\ORM\NonUniqueResultException;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
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
    public function getQuestions(ParamFetcher $paramFetcher): Response
    {
        $questions = $this->repository->getAllQuestionsDataWithScope($paramFetcher->get('scope'));

        if (empty($questions)) {
            return new JsonResponse("There are no questions to show. Please try out later.", 404);
        }

        $view = $this->view($questions, 200);
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
    public function getQuestion(Request $request, ?int $id): Response
    {
        $question = $this->repository->getQuestionById($id);

        if (empty($question)) {
            return new JsonResponse("Question with id: {$id} does not exist!", 404);
        }

        $view = $this->view($question, 200);
        return $this->handleView($view);
    }
}
