<?php namespace App\Tests;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetQuestionsCest
{
    public function _before(ApiTester $I)
    {
    }

    // tests
    public function getAllQuestionsTest(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/questions');
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['id' => 74]);
        $I->seeInDatabase('question', ['id' => 74]);
    }
}
