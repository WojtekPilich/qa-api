<?php namespace App\Tests;
use App\DataFixtures\QuestionFixtures;
use App\Entity\Questioner;
use Codeception\Util\HttpCode;

class GetQuestionsCest
{
    public function _before(ApiTester $I)
    {
        $I->loadFixtures(QuestionFixtures::class);
    }

    /**
     * Test getting all questions without scope
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
        $I->seeResponseContainsJson(['question' => []]);

        $I->dontSeeResponseCodeIs(HttpCode::BAD_REQUEST);
        $I->dontSeeResponseContainsJson(['answers' => []]);
        $I->dontSeeResponseContainsJson(['questioner' => []]);

        $I->seeInRepository(Questioner::class, ['name' => 'Bob']);
    }
}
