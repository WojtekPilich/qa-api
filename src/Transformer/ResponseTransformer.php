<?php

namespace App\Transformer;

use App\ValueObjects\QuestionValueObject;
use App\ValueObjects\QuestionsValueObject;
use App\Validator\ValidScope;

final class ResponseTransformer implements ResponseTransformerInterface
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
     * @return QuestionsValueObject
     */
    public function transform(?ValidScope $scope): QuestionsValueObject
    {
        $scopeArray = $scope ? $scope->getScope() : null;
        $result = [
            'status' => 'OK',
        ];

        /** @var QuestionValueObject $qDto */
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

        return new QuestionsValueObject($result);
    }
}