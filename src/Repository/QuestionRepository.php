<?php

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Questioner;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\NonUniqueResultException;

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
     * Returns question data with author or/and answers
     * @param array|null $scope
     * @return array
     */
    public function getAllQuestionsWithScope($scope): array
    {
        $data = [];
        $questions = $this->createQueryBuilder('q')
            ->getQuery()
            ->getResult();

        if (empty($questions)) {
            return [];
        }

        /** @var Question $question */
        foreach ($questions as $question) {

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

            /** @var Questioner $author */
            $author = $question->getQuestioner();
            $questionerData = [
                'email' => $author->getEmail(),
                'name' => $author->getName(),
                'nick' => $author->getNick(),
            ];

            $isAuthorInScope = $scope && in_array('author', $scope);
            $areAnswersInScope = $scope && in_array('answers', $scope);

            $data[] = [
                'question' => [
                    'id' => $question->getId(),
                    'content' => $question->getContent(),
                    'created_at' => $question->getCreatedAt()->format('Y-m-d H:i:s'),
                    $isAuthorInScope ? 'questioner' : null => $isAuthorInScope ? $questionerData : null,
                    $areAnswersInScope ? 'answers' : null => $areAnswersInScope ? $answersData : null,
                ],
            ];
        }

        return $data;
    }


    /**
     * Returns one question data with author or/with answers
     * @param int $id
     * @return array
     * @throws NonUniqueResultException
     */
    public function getQuestionById(int $id): array
    {
        /** @var Question|null $question */
        $question = $this->createQueryBuilder('q')
            ->andWhere('q.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();

        if (! ($question instanceof Question)) {
            return [];
        }

        /** @var Questioner $author */
        $author = $question->getQuestioner();

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

        return [
            'id' => $question->getId(),
            'content' => $question->getContent(),
            'created_at' => $question->getCreatedAt()->format('Y-m-d H:i:s'),
            'questioner' => [
                'email' => $author->getEmail(),
                'name' => $author->getName(),
                'nick' => $author->getNick(),
            ],
            'answers' => $answersData,
        ];
    }
}
