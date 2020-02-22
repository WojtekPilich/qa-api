<?php declare(strict_types=1);

namespace App\Exception;

final class NotFound extends \Exception
{
    public static function with(string $message, int $code)
    {
        return new self($message, $code);
    }
}