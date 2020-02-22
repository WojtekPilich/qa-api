<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

final class WrongQueryParameter extends Exception
{
    public static function with(string $key)
    {
        $message = sprintf('Wrong query parameter: %s', $key);

        return new self($message, Response::HTTP_BAD_REQUEST);
    }
}