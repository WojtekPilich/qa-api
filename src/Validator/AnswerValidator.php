<?php declare(strict_types=1);

namespace App\Validator;

use App\Exception\EmptyAnswer;
use App\Exception\ForbiddenWord;
use App\Exception\InvalidLengthOfAnswerParameter;
use App\Exception\NoAnswer;
use App\Repository\ForbiddenWordRepository;
use App\ValueObjects\AnswerValueObject;

final class AnswerValidator implements Verifiable
{
    private ForbiddenWordRepository $forbiddenWordRepo;

    /**
     * AnswerValidator constructor.
     * @param ForbiddenWordRepository $forbiddenWordRepo
     */
    public function __construct(ForbiddenWordRepository $forbiddenWordRepo)
    {
        $this->forbiddenWordRepo = $forbiddenWordRepo;
    }

    /**
     * @param AnswerValueObject $answerValueObject
     * @return ValidAnswer
     * @throws EmptyAnswer
     * @throws InvalidLengthOfAnswerParameter
     * @throws ForbiddenWord
     * @throws NoAnswer
     */
    public function validate($answerValueObject)
    {
        if ($answerValueObject->hasEmptyContents()) {
            throw EmptyAnswer::withMessage('Answer contents cannot be empty!');
        }

        if ($answerValueObject->isNotProvided()) {
            throw NoAnswer::withMessage('No answer provided!');
        }

        if (strlen($answerValueObject->contents()) > 255) {
            throw InvalidLengthOfAnswerParameter::withMessage('Answer Parameter is too long! Maximally 255 characters allowed.');
        }

        $forbiddenWords = $this->forbiddenWordRepo->findAll();
        foreach ($forbiddenWords as $forbiddenWord) {
            if (strpos(strtolower($answerValueObject->contents()), $forbiddenWord->getName()) !== false) {
                throw ForbiddenWord::whichIs($forbiddenWord->getName());
            }
        }

        return new ValidAnswer($answerValueObject->contents());
    }
}