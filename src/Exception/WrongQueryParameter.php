<?php declare(strict_types=1);

use Symfony\Component\HttpFoundation\Response;

final class WrongQueryParameter extends Exception
{
    /**
     * @param string $key
     * @return WrongQueryParameter
     */
    public static function whichIs(string $key): self
    {
        $message = sprintf('Wrong query parameter: %s', $key);

        return new self($message, Response::HTTP_BAD_REQUEST);
    }
}