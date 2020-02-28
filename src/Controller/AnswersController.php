<?php declare(strict_types=1);

namespace App\Controller;

use App\Exception\EmptyAnswer;
use App\Exception\NoAnswer;
use App\Manager\AnswerManager;
use App\Mapper\JsonMapper;
use App\Message\Command\Answer;
use App\Validator\AnswerValidator;
use App\Validator\NickValidator;
use App\ValueObjects\AnswerValueObject;
use App\ValueObjects\NickValueObject;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\Annotations\RequestParam;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class AnswersController extends AbstractFOSRestController
{
    private AnswerValidator $answerValidator;

    private NickValidator $nickValidator;

    private AnswerManager $manager;

    /**
     * QuestionController constructor.
     * @param AnswerValidator $answerValidator
     * @param NickValidator $nickValidator
     * @param AnswerManager $manager
     */
    public function __construct(AnswerValidator $answerValidator, NickValidator $nickValidator, AnswerManager $manager)
    {
        $this->answerValidator = $answerValidator;
        $this->nickValidator = $nickValidator;
        $this->manager = $manager;
    }

    /**
     * Adds answer to question for given id
     * @Rest\Post("/questions/{id}/answer", name="answer_add")
     * @RequestParam(name="answer", nullable=false, description="Answer to given question")
     * @RequestParam(name="nick", nullable=true, description="Answerer nick")
     * @param Request $request
     * @param int $id
     * @param JsonMapper $mapper
     * @return Response
     */
    public function postAnswerAction(Request $request, JsonMapper $mapper, int $id): Response
    {
        try {
            $answer = new AnswerValueObject($request->get('answer'));
            $nick = new NickValueObject($request->get('nick'));

            $validAnswer = $this->answerValidator->validate($answer);
            $validNick = null;

            if ($nick->isProvided()) {
                $validNick = $this->nickValidator->validate($nick);
            }

            return $this->manager->save(new Answer(
                $validAnswer->contents(),
                $validNick ? $validNick->contents() : null,
                $id
            ));

        } catch (\Exception | EmptyAnswer | NoAnswer $exception) {
            return $mapper->handle($exception);
        }
    }
}
