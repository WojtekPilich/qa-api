<?php

namespace App\Controller;

use App\Manager\QuestionManager;
use App\Mapper\JsonMapper;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Response;

class QuestionController extends AbstractFOSRestController
{
    private QuestionManager $manager;

    /**
     * QuestionController constructor.
     * @param QuestionManager $manager
     */
    public function __construct(QuestionManager $manager)
    {
        $this->manager = $manager;
    }

    /**
     * Gets one question
     * @Rest\Get("/questions/{id}", name="question_get")
     * @param int $id
     * @param JsonMapper $mapper
     * @return Response
     */
    public function getQuestionAction(int $id, JsonMapper $mapper): Response
    {
        try {
            return $mapper->map($this->manager->prepareResponseFor($id));

        } catch(\Exception $exception) {
            return $mapper->handle($exception);
        }
    }
}
