<?php declare(strict_types=1);

namespace App\Validator;

use App\Exception\EmptyNick;
use App\Exception\ForbiddenWord;
use App\Exception\InvalidLengthOfAnswerParameter;
use App\Repository\ForbiddenWordRepository;
use App\ValueObjects\NickValueObject;

final class NickValidator implements Verifiable
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
     * @param NickValueObject $nickValueObject
     * @return ValidNick
     * @throws InvalidLengthOfAnswerParameter
     * @throws ForbiddenWord
     * @throws EmptyNick
     */
    public function validate($nickValueObject)
    {
        if ($nickValueObject->hasEmptyContents()){
            throw EmptyNick::withMessage('Nick contents cannot be empty!');
        }

        if (strlen($nickValueObject->contents()) > 255) {
            throw InvalidLengthOfAnswerParameter::withMessage('Nick Parameter is too long! Maximally 255 characters allowed.');
        }

        $forbiddenWords = $this->forbiddenWordRepo->findAll();
        foreach ($forbiddenWords as $forbiddenWord) {
            if (strpos(strtolower($nickValueObject->contents()), $forbiddenWord->getName()) !== false) {
                throw ForbiddenWord::whichIs($forbiddenWord->getName());
            }
        }

        return new ValidNick($nickValueObject->contents());
    }
}