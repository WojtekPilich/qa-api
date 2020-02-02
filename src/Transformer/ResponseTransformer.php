<?php

namespace App\Transformer;

use App\DTO\QuestionDTO;
use App\DTO\QuestionsDTO;
use App\Validator\ValidScope;

class ResponseTransformer implements ResponseTransformerInterface
{
    /** @var array $data */
    private $data;

    /**
     * @param array $data
     */
    public function setData(array $data)
    {
        $this->data = $data;
    }

    /**
     * ResponseTransformer constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->setData($data);
    }


    /**
     * @param ValidScope $scope
     * @return QuestionsDTO
     */
    public function transform(?ValidScope $scope): QuestionsDTO
    {
        $scopeArray = $scope ? $scope->getScope() : null;
        $result = [
            'status' => 'OK',
        ];
        $questionerData = [];
        $answersData = [];

        /** @var QuestionDTO $qDto */
        foreach ($this->data as $qDto) {

            $questionerData = $qDto->getQuestioner();
            $answersData = $qDto->getAnswers();

            $result['questions'][] = [
                'id' => $qDto->getId(),
                'content' => $qDto->getContent(),
                'created_at' => $qDto->getCreatedAt()->format('Y-m-d H:i:s'),
            ];
        }

        foreach ($result['questions'] as $key => &$value) {
            if ($scope && in_array('author', $scopeArray)) {
                $value['questioner'] = $questionerData;
            }
            if ($scope && in_array('answers', $scopeArray)) {
                $value['answers'] = $answersData;
            }
        }

        return new QuestionsDTO($result);
    }
}