<?php declare(strict_types=1);

namespace App\Transformer;

use App\ValueObjects\QuestionValueObject;
use App\ValueObjects\QuestionsValueObject;
use App\Validator\ValidScope;

final class ResponseTransformer implements QuestionsTransformable, QuestionTransformable
{
    /** @var array $data */
    private $data;

    /**
     * ResponseTransformer constructor.
     * @param iterable | QuestionValueObject $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * @param ValidScope $scope
     * @return QuestionsValueObject
     */
    public function transformQuestions(?ValidScope $scope): QuestionsValueObject
    {
        $scopeArray = $scope ? $scope->getScope() : null;
        $result = [
            'status' => 'OK',
        ];

        /** @var QuestionValueObject $qVo */
        foreach ($this->data as $qVo) {
            $result['questions'][] = [
                'id' => $qVo->id(),
                'content' => $qVo->content(),
                'created_at' => $qVo->createdAt()->format('Y-m-d H:i:s'),
                'questioner' => $qVo->questioner(),
                'answers' => $qVo->answers(),
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

    /**
     * @return iterable
     */
    public function transformQuestion(): iterable
    {
        return [
            'status' => 'OK',
            'id' => $this->data->id(),
            'content' => $this->data->content(),
            'created_at' => $this->data->createdAt()->format('Y-m-d'),
            'questioner' => $this->data->questioner(),
            'answers' => $this->data->answers(),
        ];
    }
}