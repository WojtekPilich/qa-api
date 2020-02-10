<?php

namespace App\Exception;

final class NotFound extends \Exception
{
    public static function with(string $message, int $code)
    {
        return new self($message, $code);
    }
}