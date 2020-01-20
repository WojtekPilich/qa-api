<?php

namespace App\Tests\api;

use App\DataFixtures\QuestionerFixtures;
use App\DataFixtures\QuestionFixtures;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class GetQuestionCest
{
    public function _before(ApiTester $I)
    {
        $I->loadFixtures(QuestionerFixtures::class);
        $I->loadFixtures(QuestionFixtures::class);
    }

    /**
     * @param ApiTester $I
     */
    public function getQuestionWithCorrectId(ApiTester $I)
    {
        $questionerId = $I->grabFromDatabase('question', 'id', ['content' => 'Who killed Jabba?']);
        $I->seeInDatabase('question', ['content' => 'Who killed Jabba?']);

        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/questions/'.$questionerId);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::OK);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['content' => 'Who killed Jabba?']);
        $I->seeResponseContainsJson(['questioner' => ['name' => 'Jack']]);
        $I->seeResponseContainsJson(['answers' => []]);

        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @param ApiTester $I
     */
    public function getQuestionWithWrongId(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendGET('/questions/999');
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseCodeIsClientError();
        $I->dontSeeResponseContainsJson(['content' => 'Who killed Jabba?']);
        $I->dontSeeResponseContainsJson(['status' => 'ok']);

        $I->dontSeeResponseCodeIs(HttpCode::OK);
    }
}