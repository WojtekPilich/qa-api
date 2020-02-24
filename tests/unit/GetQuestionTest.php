<?php

namespace App\Tests\unit;

use App\Message\Query\Question;
use Codeception\Test\Unit;

class GetQuestionTest extends Unit
{
    public function testGetQuestionQuery()
    {
        $message = new Question(15);
        $this->assertClassHasAttribute('id', Question::class);
        $this->assertEquals(15, $message->getId());
    }
}