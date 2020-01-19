<?php

namespace App\MessageHandler\Command;

use App\Entity\Answer;

class ValidationHelper
{
    public static function checkParamLength ($param)
    {
        return strlen($param) > 255 ? false : true;
    }

    public static function checkForbiddenWords($param)
    {
        foreach (Answer::$forbiddenWords as $forbiddenWord) {
            return strpos($forbiddenWord, strtolower($param)) ? false : true;
        }
    }
}