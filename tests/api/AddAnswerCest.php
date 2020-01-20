<?php

namespace App\Tests\api;

use App\DataFixtures\QuestionerFixtures;
use App\DataFixtures\QuestionFixtures;
use App\Tests\ApiTester;
use Codeception\Util\HttpCode;

class AddAnswerCest
{
    public function _before(ApiTester $I)
    {
        $I->loadFixtures(QuestionerFixtures::class);
        $I->loadFixtures(QuestionFixtures::class);
    }

    /**
     * @param ApiTester $I
     */
    public function addAnswerByAnonymusAnswerer(ApiTester $I)
    {
        $questionId = $I->grabFromDatabase('question', 'id', ['content' => 'Who killed Jabba?']);
        $I->seeInDatabase('question', ['content' => 'Who killed Jabba?']);

        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/questions/'.$questionId.'/answer', ['answer' => 'test1']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['status' => 'created']);
        $I->seeResponseContains('http:\/\/127.0.0.1:8000\/questions\/'.$questionId);
        $I->seeInDatabase('answer', ['content' => 'test1', 'question_id' => $questionId]);

        $questsionerId = $I->grabFromDatabase('answer', 'answerer_id', ['content' => 'test1']);
        $I->seeInDatabase('answerer', ['id' => $questsionerId, 'nick' => 'Anonymus']);

        $I->dontSeeInDatabase('answerer', ['id' => $questsionerId, 'nick' => 'MyFancyNick']);
        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @param ApiTester $I
     */
    public function addAnswerByAnswererWithNick(ApiTester $I)
    {
        $questionId = $I->grabFromDatabase('question', 'id', ['content' => 'Where did the Clone Wars begin?']);
        $I->seeInDatabase('question', ['content' => 'Where did the Clone Wars begin?']);

        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/questions/'.$questionId.'/answer', ['answer' => 'test2', 'nick' => 'Rob']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::CREATED);
        $I->seeResponseCodeIsSuccessful();
        $I->seeResponseContainsJson(['status' => 'created']);
        $I->seeResponseContains('http:\/\/127.0.0.1:8000\/questions\/'.$questionId);
        $I->seeInDatabase('answer', ['content' => 'test2', 'question_id' => $questionId]);

        $questionerId = $I->grabFromDatabase('answer', 'answerer_id', ['content' => 'test2']);
        $I->seeInDatabase('answerer', ['id' => $questionerId, 'nick' => 'Rob']);

        $I->dontSeeInDatabase('answerer', ['id' => $questionerId, 'nick' => 'Anonymus']);
        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
    }

    /**
     * @param ApiTester $I
     */
    public function addAnswerWithoutContent(ApiTester $I)
    {
        $questionId = $I->grabFromDatabase('question', 'id', ['content' => 'Where did the Clone Wars begin?']);
        $I->seeInDatabase('question', ['content' => 'Where did the Clone Wars begin?']);

        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/questions/'.$questionId.'/answer', []);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseContainsJson(['details' => 'answer parameter is required.']);
        $I->dontSeeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param ApiTester $I
     */
    public function addAnswerWithWrongQuestionId(ApiTester $I)
    {
        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/questions/999/answer', []);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseContainsJson(['details' => 'question does not exist!']);
        $I->dontSeeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param ApiTester $I
     */
    public function addTooLongAnswer(ApiTester $I)
    {
        $lorem = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent in lorem posuere, commodo mi vitae, molestie libero. Vestibulum molestie fringilla ante, a lacinia nisi posuere vel. Duis ac odio vel massa tincidunt posuere. Proin consequat eu nunc nec varius. Quisque non erat porttitor arcu consequat auctor eu in leo. Quisque eu sapien fringilla, tempor dolor nec, elementum purus. Sed viverra diam libero, et dictum elit suscipit ornare. Curabitur vehicula gravida tortor vitae tempus. Vestibulum gravida quis urna id posuere. Morbi feugiat ante sodales elit varius, et venenatis nunc cursus. In a nibh sit amet massa eleifend porta sed eget est.";

        $questionId = $I->grabFromDatabase('question', 'id', ['content' => 'Where did the Clone Wars begin?']);
        $I->seeInDatabase('question', ['content' => 'Where did the Clone Wars begin?']);

        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/questions/'.$questionId.'/answer', ['answer' => $lorem]);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseContainsJson(['details' => 'request content is too long.']);
        $I->dontSeeResponseCodeIs(HttpCode::OK);
    }

    /**
     * @param ApiTester $I
     */
    public function addAnswerWithForbiddenWord(ApiTester $I)
    {
        $questionId = $I->grabFromDatabase('question', 'id', ['content' => 'Where did the Clone Wars begin?']);
        $I->seeInDatabase('question', ['content' => 'Where did the Clone Wars begin?']);

        $I->haveHttpHeader('accept', 'application/json');
        $I->haveHttpHeader('content-type', 'application/json');
        $I->sendPOST('/questions/'.$questionId.'/answer', ['answer' => 'Ooops, I said fuck!']);
        $I->seeResponseIsJson();
        $I->seeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->seeResponseCodeIsClientError();
        $I->seeResponseContainsJson(['details' => 'request body contains forbidden words.']);
        $I->dontSeeResponseCodeIs(HttpCode::OK);
    }
}