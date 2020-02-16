<?php

namespace App\Controller;

use App\Manager\QuestionsManager;
use App\Mapper\JsonMapper;
use App\Scope\Scope;
use App\Validator\QuestionsValidator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use WrongQueryParameter;

/**
 * Question controller.
 * @Route("/",name="api_")
 */
class QuestionsController extends AbstractFOSRestController
{
    /**
     * @var QuestionsManager
     */
    private $manager;

    /**
     * QuestionController constructor.
     * @param QuestionsManager $manager
     */
    public function __construct(QuestionsManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Lists all Questions
     * @Rest\Get("/questions", name="questions_get")
     * @QueryParam(map=true, name="scope", strict=true, nullable=true, description="author or answers")
     * @param ParamFetcher $paramFetcher
     * @param QuestionsValidator $validator
     * @param JsonMapper $mapper
     * @return Response
     */
    public function getQuestionsAction(ParamFetcher $paramFetcher, QuestionsValidator $validator, JsonMapper $mapper): Response
    {
        try {
            $scope = new Scope($paramFetcher->get('scope'));

            if ($scope->hasParameters()) {
                $validScope = $validator->validate($scope);
            }
            return $mapper->map($this->manager->prepareResponseFor($validScope ?? null));
        } catch(\Exception | WrongQueryParameter $exception) {
            return $mapper->handle($exception);
        }
    }
}
