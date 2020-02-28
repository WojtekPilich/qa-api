<?php declare(strict_types=1);

namespace App\Repos;

use App\Entity\Answer;
use App\Entity\Answerer;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;

final class AnswerRepo implements AnswerAddable
{
    private QuestionRepository $questionRepository;

    private EntityManagerInterface $entityManager;

    /**
     * AnswerRepo constructor.
     * @param QuestionRepository $questionRepository
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(QuestionRepository $questionRepository, EntityManagerInterface $entityManager)
    {
        $this->questionRepository = $questionRepository;
        $this->entityManager = $entityManager;
    }

    public function addAnswer(\App\Message\Command\Answer $answer)
    {
        $question = $this->questionRepository->findOneBy([
            'id' => $answer->questionId()
        ]);

        $answerer = (new Answerer())
            ->setNick($answer->nick() ?? 'Anonymus');
        $this->entityManager->persist($answerer);

        $answer = (new Answer())
            ->setContent($answer->answer())
            ->setAnswerer($answerer)
            ->setQuestion($question);

        $question->addAnswer($answer);

        $this->entityManager->persist($answer);
        $this->entityManager->flush();
    }
}