<?php

namespace App\Tests\unit;

use App\Message\Query\GetQuestion;
use Codeception\Test\Unit;

class GetQuestionTest extends Unit
{
    public function testGetQuestionQuery()
    {
        $message = new GetQuestion(15);
        $this->assertClassHasAttribute('id', GetQuestion::class);
        $this->assertEquals(15, $message->getId());
    }
}