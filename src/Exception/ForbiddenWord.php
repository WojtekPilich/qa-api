<?php declare(strict_types=1);

namespace App\Exception;

use Symfony\Component\HttpFoundation\Response;

final class ForbiddenWord extends \Exception
{
    /**
     * @param string $key
     * @return ForbiddenWord
     */
    public static function whichIs(string $key): self
    {
        $message = sprintf('Request body contains the following forbidden word: %s', $key);

        return new self($message, Response::HTTP_BAD_REQUEST);
    }
}