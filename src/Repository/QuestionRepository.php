<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questioner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

/**
 * @method Question|null find($id, $lockMode = null, $lockVersion = null)
 * @method Question|null findOneBy(array $criteria, array $orderBy = null)
 * @method Question[]    findAll()
 * @method Question[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * Returns only id and content of each existing question
     * @return array
     */
    public function getAllQuestionsContent(): array
    {
        $data = [];
        $questions = $this->createQueryBuilder('q')
            ->getQuery()
            ->getResult();

        /** @var Question $question */
        foreach ($questions as $question) {
            $data[] = [
                'id' => $question->getId(),
                'content' => $question->getContent(),
            ];
        }

        return $data;
    }

    /**
     * Returns question data with author or/and answers
     * @param array $scope
     * @return array
     */
    public function getAllQuestionsDataWithScope(array $scope): array
    {
        $data = [];
        $questions = $this->createQueryBuilder('q')
            ->getQuery()
            ->getResult();

        /** @var Question $question */
        foreach ($questions as $question) {

            /** @var Questioner $author */
            $author = $question->getQuestioner();

            /** @var Answer[] $answers */
            $answers = $question->getAnswers();

            $answersData = [];
            foreach ($answers as $answer) {
                $answersData[] = [
                    'content' => $answer->getContent(),
                    'answerer' => $answer->getAnswerer()->getNick(),
                ];
            }

            if ($scope['author']) {
                $data[] = [
                    'id' => $question->getId(),
                    'content' => $question->getContent(),
                    'questioner' => [
                        'email' => $author->getEmail(),
                        'name' => $author->getName(),
                        'nick' => $author->getNick(),
                    ],
                ];
            }

            if ($scope['answers']) {
                $data[] = [
                    'id' => $question->getId(),
                    'content' => $question->getContent(),
                    'answers' => $answersData,
                ];
            }
        }

        return $data;
    }
}
