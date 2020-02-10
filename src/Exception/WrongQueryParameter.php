<?php

final class WrongQueryParameter extends Exception
{
    public static function withString(string $key)
    {
        $message = sprintf('Wrong query parameter: %s', $key);

        return new self($message);
    }
}