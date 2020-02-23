<?php declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

final class NotFound extends \Exception
{
    /**
     * @param string $message
     * @return NotFound
     */
    public static function with(string $message)
    {
        return new self($message, Response::HTTP_NOT_FOUND);
    }
}