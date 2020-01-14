<?php

namespace App\DataFixtures;

use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionFixtures extends Fixture implements DependentFixtureInterface
{
    /**
     * First set of questions.
     * @var array
     */
    private static $bobQuestions = [
        'Which stormtrooper wasn\'t able to complete his mission in "Star Wars: The Force Awakens?"',
        'What kind of vehicle did Rey live in?',
        'According to Master Yoda, how many Sith are always out there?',
    ];

    /**
     * Second set of questions.
     * @var array
     */
    private static $jackQuestions = [
        'What happened to Anakin Skywalker during the battle with Count Dooku?',
        'Who played the part of Commander Cody?',
        'Who killed Jabba?',
    ];

    /**
     * Third set of questions.
     * @var array
     */
    private static $jennyQuestions = [
        'What did Luke Skywalker lose in his fight with Darth Vader?',
        'According to the Emperor, what was Luke Skywalker\'s weakness?',
        'Where did the Clone Wars begin?',
        'What did Owen Lars tell Luke Skywalker about his father?',
    ];

    /**
     * Creates question objects binds them to questioners using references
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::$bobQuestions as $content) {
            $question = new Question();
            $question
                ->setContent($content);

            $manager->persist($question);
            $this->getReference(QuestionerFixtures::BOB_REFERENCE)->addQuestion($question);
        }

        foreach (self::$jackQuestions as $content) {
            $question = new Question();
            $question
                ->setContent($content);

            $manager->persist($question);
            $this->getReference(QuestionerFixtures::JACK_REFERENCE)->addQuestion($question);
        }

        foreach (self::$jennyQuestions as $content) {
            $question = (new Question());
            $question
                ->setContent($content);

            $manager->persist($question);
            $this->getReference(QuestionerFixtures::JENNY_REFERENCE)->addQuestion($question);
        }

        $manager->flush();
    }

    /**
     * Runs Questioner Fixtures first.
     * @inheritDoc
     */
    public function getDependencies()
    {
        return [
            QuestionerFixtures::class,
        ];
    }
}
