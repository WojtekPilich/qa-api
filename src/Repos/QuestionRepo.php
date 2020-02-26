<?php declare(strict_types=1);

namespace App\Repos;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questioner;
use App\Exception\NotFound;
use App\Repository\QuestionRepository;
use App\ValueObjects\QuestionValueObject;

final class QuestionRepo implements QuestionFetchable
{
    private QuestionRepository $questionRepository;

    /**
     * QuestionsRepo constructor.
     * @param QuestionRepository $questionRepository
     */
    public function __construct(QuestionRepository $questionRepository)
    {
        $this->questionRepository = $questionRepository;
    }

    /**
     * @param int $id
     * @return QuestionValueObject
     * @throws NotFound
     */
    public function getQuestion(int $id): QuestionValueObject
    {
        $question = $this->questionRepository->findOneBy(['id' => $id]);

        if (! ($question instanceof Question)) {
            throw NotFound::withMessage('Question not found!');
        }

        /** @var Questioner $author */
        $author = $question->getQuestioner();
        $questionerData = [
            'email' => $author->getEmail(),
            'name' => $author->getName(),
            'nick' => $author->getNick(),
        ];

        /** @var Answer[] $answers */
        $answers = $question->getAnswers();

        $answersData = [];
        foreach ($answers as $answer) {
            $answersData[] = [
                'id' => $answer->getId(),
                'content' => $answer->getContent(),
                'created_at' => $answer->getCreatedAt()->format('Y-m-d H:i:s'),
                'answerer_nick' => $answer->getAnswerer()->getNick(),
            ];
        }

        return new QuestionValueObject(
            $question->getId(),
            $question->getContent(),
            $question->getCreatedAt(),
            $questionerData,
            $answersData
        );
    }
}