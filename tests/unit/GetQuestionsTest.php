<?php

namespace App\Tests;

use App\Message\Query\GetQuestions;
use Codeception\Test\Unit;

class GetQuestionsTest extends Unit
{
    public function testGetQuestionsQuery()
    {
        $message = new GetQuestions(['author']);
        $this->assertClassHasAttribute('scope', GetQuestions::class);
        $this->assertEquals(['author'], $message->getScope());
    }
}