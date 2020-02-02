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
        $result = [];
        $questionerData = [];
        $answersData = [];

        /** @var QuestionDTO $dto */
        foreach ($this->data as $dto) {

            $questionerData = $dto->getQuestioner();
            $answersData = $dto->getAnswers();

            $result['questions'][] = [
                'id' => $dto->getId(),
                'content' => $dto->getContent(),
                'created_at' => $dto->getCreatedAt()->format('Y-m-d H:i:s'),
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