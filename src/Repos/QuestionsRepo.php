<?php declare(strict_types=1);

namespace App\Repos;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questioner;
use App\Exception\NotFound;
use App\Repository\QuestionRepository;
use App\ValueObjects\QuestionValueObject;
use Symfony\Component\HttpFoundation\Response;

final class QuestionsRepo implements QuestionsFetchable
{
    /**
     * @var QuestionRepository
     */
    private $questionRepository;

    /**
     * QuestionsRepo constructor.
     * @param QuestionRepository $questionRepository
     */
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @return iterable
     * @throws \Exception
     * @return iterable
     */
    public function getQuestions(): iterable
    {
        $questions = $this->questionRepository->findAll();
        $data = [];

        if (empty($questions)) {
            throw NotFound::with('No questions found!', Response::HTTP_NOT_FOUND);
        }

        /** @var Question $question */
        foreach ($questions as $question) {
            $answersData = [];

            /** @var Answer $answer */
            $answers = $question->getAnswers();
            foreach ($answers as $answer) {
                $answersData[] = [
                    'id' => $answer->getId(),
                    'content' => $answer->getContent(),
                    'created_at' => $answer->getCreatedAt()->format('Y-m-d H:i:s'),
                    'answerer_nick' => $answer->getAnswerer()->getNick(),
                ];
            }

            /** @var Questioner $author */
            $author = $question->getQuestioner();
            $questionerData = [
                'email' => $author->getEmail(),
                'name' => $author->getName(),
                'nick' => $author->getNick(),
            ];

            $valueObject = new QuestionValueObject(
                $question->getId(),
                $question->getContent(),
                $question->getCreatedAt(),
                $questionerData,
                $answersData
            );

            $data[]= $valueObject;
        }

        return $data;
    }
}