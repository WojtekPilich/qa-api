<?php namespace App\Tests;
use App\DataFixtures\QuestionFixtures;
use Codeception\Util\HttpCode;

class GetQuestionsCest
{
    public function _before(ApiTester $I)
    {
        $I->loadFixtures(QuestionFixtures::class);
    }

    /**
     * @param ApiTester $I
     */
    public function getQuestionsWithoutScope(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/questions');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['questions' => []]);

        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->dontSeeResponseContainsJson(['answers' => []]);
        $I->dontSeeResponseContainsJson(['questioner' => []]);
    }

    /**
     * @param ApiTester $I
     */
    public function getQuestionsWithAuthorScope(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/questions', ['scope[]' => 'author']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['questioner' => []]);

        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->dontSeeResponseContainsJson(['answers' => []]);
    }
}
