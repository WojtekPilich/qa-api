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

        /** @var QuestionDTO $qDto */
        foreach ($this->data as $qDto) {
            $result['questions'][] = [
                'id' => $qDto->getId(),
                'content' => $qDto->getContent(),
                'created_at' => $qDto->getCreatedAt()->format('Y-m-d H:i:s'),
                'questioner' => $qDto->getQuestioner(),
                'answers' => $qDto->getAnswers(),
            ];
        }

        foreach ($result['questions'] as $key => &$value) {
            if (! $scope || ! in_array('author', $scopeArray)) {
                unset($value['questioner']);
            }
            if (! $scope || ! in_array('answers', $scopeArray)) {
                unset($value['answers']);
            }
        }

        return new QuestionsDTO($result);
    }
}