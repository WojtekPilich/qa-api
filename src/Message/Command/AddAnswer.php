<?php

namespace App\Message\Command;

use Symfony\Component\HttpFoundation\Request;

class AddAnswer
{
    private $request;

    private $id;

    public function __construct(Request $request, int $id)
    {
        $this->request = $request;
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }
}