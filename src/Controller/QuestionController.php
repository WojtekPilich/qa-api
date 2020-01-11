<?php

namespace App\Controller;

use App\Repository\AnswerRepository;
use App\Repository\QuestionRepository;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Question controller.
 * @Route("/",name="api_")
 */
class QuestionController extends AbstractFOSRestController
{
    /** @var QuestionRepository $questionRepo */
    private $questionRepo;

    /** @var AnswerRepository $answerRepo */
    private $answerRepo;

    public function __construct(QuestionRepository $qr, AnswerRepository $ar)
    {
        $this->questionRepo = $qr;
        $this->answerRepo = $ar;
    }

    /**
     * List all Questions
     * @Rest\Get("/questions")
     */
    public function getQuestions()
    {
        $questions = $this->questionRepo->findAll();

        return $this->json($questions);
    }
}
