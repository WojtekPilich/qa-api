<?php

namespace App\Controller;

use App\Manager\QuestionsManager;
use App\Mapper\ResponseMapper;
use App\Storage\QuestionsRequestStorage;
use App\Validator\QuestionsValidator;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\QueryParam;
use FOS\RestBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;

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
     * @param ResponseMapper $mapper
     * @return Response
     */
    public function getQuestionsAction(ParamFetcher $paramFetcher, QuestionsValidator $validator, ResponseMapper $mapper): Response
    {
        $scope = $paramFetcher->get('scope');
        $validScope = null;

        if ($scope) {
            try {
                $validScope = $validator->validate($scope);
            } catch (\Exception $exception) {
                return $mapper->mapExceptionToJson($exception);
            }
        }

        try {
            $results = $this->manager->prepareResult(new QuestionsRequestStorage($validScope ?? null));
            return $mapper->mapResponseToJson($results);
        } catch(\Exception $exception) {
            return $mapper->mapExceptionToJson($exception);
        }
    }
}
