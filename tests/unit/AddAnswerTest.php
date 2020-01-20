<?php

namespace App\Tests\unit;

use App\Message\Command\AddAnswer;
use Codeception\Test\Unit;
use Symfony\Component\HttpFoundation\Request;

class AddAnswerTest extends Unit
{
    public function testAddAnswerCommand()
    {
        $request = new Request();

        $message = new AddAnswer($request, 5);
        $this->assertClassHasAttribute('request', AddAnswer::class);
        $this->assertClassHasAttribute('id', AddAnswer::class);
        $this->assertEquals($request, $message->getRequest());
        $this->assertEquals(5, $message->getId());
    }
}