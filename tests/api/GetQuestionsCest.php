<?php

namespace App\Tests;

use App\DataFixtures\QuestionerFixtures;
use App\DataFixtures\QuestionFixtures;
use Codeception\Util\HttpCode;

class GetQuestionsCest
{
    public function _before(ApiTester $I)
    {
        $I->loadFixtures(QuestionerFixtures::class);
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
        $I->seeResponseContainsJson(['name' => 'Jenny']);

        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->dontSeeResponseContainsJson(['answers' => []]);
    }

    /**
     * @param ApiTester $I
     */
    public function getQuestionsWithAnswersScope(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/questions', ['scope[]' => 'answers']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['answers' => []]);

        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->dontSeeResponseContainsJson(['questioner' => []]);
    }

    /**
     * @param ApiTester $I
     */
    public function getQuestionsWithFullScope(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/questions?scope[]=author&scope[]=answers');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['answers' => []]);
        $I->seeResponseContainsJson(['questioner' => []]);

        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @param ApiTester $I
     */
    public function getQuestionsWithWrongScopeParameter(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/questions', ['scope[]' => 'xxx']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseContainsJson(['details' => 'wrong query parameters.']);

        $I->dontSeeResponseCodeIs(HttpCode::OK);
        $I->dontSeeResponseContainsJson(['questioner' => []]);
    }
}
