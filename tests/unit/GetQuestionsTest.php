<?php

namespace App\Tests;

use App\Message\Query\Questions;
use Codeception\Test\Unit;

class GetQuestionsTest extends Unit
{
    public function testGetQuestionsQuery()
    {
        $message = new Questions(['author']);
        $this->assertClassHasAttribute('scope', Questions::class);
        $this->assertEquals(['author'], $message->scope());
    }
}