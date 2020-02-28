<?php declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

final class NoAnswer extends \Exception
{
    /**
     * @param string $message
     * @return NoAnswer
     */
    public static function withMessage(string $message): self
    {
        return new self($message, Response::HTTP_BAD_REQUEST);
    }
}