<?php

namespace App\Tests\unit;

use App\Message\Command\Answer;
use Codeception\Test\Unit;
use Symfony\Component\HttpFoundation\Request;

class AddAnswerTest extends Unit
{
    public function testAddAnswerCommand()
    {
        $request = new Request();

        $message = new Answer($request, 5);
        $this->assertClassHasAttribute('request', Answer::class);
        $this->assertClassHasAttribute('id', Answer::class);
        $this->assertEquals($request, $message->getRequest());
        $this->assertEquals(5, $message->getId());
    }
}