<?php declare(strict_types=1);

namespace App\Transformer;

use App\ValueObjects\QuestionValueObject;
use App\ValueObjects\QuestionsValueObject;
use App\Validator\ValidScope;

final class ResponseTransformer implements Transformable
{
    /** @var array $data */
    private $data;

    /**
     * ResponseTransformer constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
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
}