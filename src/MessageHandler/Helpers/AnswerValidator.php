<?php

namespace App\MessageHandler\Helpers;

class AnswerValidator
{
    /** @var array|iterable $forbiddenWords */
    private $forbiddenWords = [];

    /**
     * AnswerValidator constructor.
     * @param array|iterable $forbiddenWords
     */
    public function __construct(Iterable $forbiddenWords)
    {
        $this->forbiddenWords = $forbiddenWords;
    }

    /**
     * Checks param length
     * @param $param
     * @return bool
     */
    public function checkLength($param)
    {
        return strlen($param) > 255 ? false : true;
    }

    /**
     * Searches forbidden words
     * @param $param
     * @return bool
     */
    public function searchForbiddenWords($param)
    {
        foreach ($this->forbiddenWords as $forbiddenWord) {
            if (strpos(strtolower($param), $forbiddenWord->getName()) !== false) {
                return false;
            }
        }

        return true;
    }
}