<?php

namespace App\DataFixtures;

use App\Entity\Question;
use App\Entity\Questioner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionFixtures extends Fixture
{
    private static $questionBlock1 = [
        'Which stormtrooper wasn\'t able to complete his mission in "Star Wars: The Force Awakens?"',
        'What kind of vehicle did Rey live in?',
        'According to Master Yoda, how many Sith are always out there?',
    ];

    private static $questionBlock2 = [
        'What happened to Anakin Skywalker during the battle with Count Dooku?',
        'Who played the part of Commander Cody?',
        'Who killed Jabba?',
    ];

    private static $questionBlock3 = [
        'What did Luke Skywalker lose in his fight with Darth Vader?',
        'According to the Emperor, what was Luke Skywalker\'s weakness?',
        'Where did the Clone Wars begin?',
        'What did Owen Lars tell Luke Skywalker about his father?',
    ];

    public function load(ObjectManager $manager)
    {
        $questioner1 = new Questioner();
        $questioner1
            ->setEmail('bob@example.com')
            ->setName('Bob')
            ->setNick('BigRobo');

        $questioner2 = new Questioner();
        $questioner2
            ->setEmail('jack@example.com')
            ->setName('Jack')
            ->setNick('SmallHit');

        $questioner3 = new Questioner();
        $questioner3
            ->setEmail('jenny@example.com')
            ->setName('Jenny')
            ->setNick('NiceTit');

        $manager->persist($questioner1);
        $manager->persist($questioner2);
        $manager->persist($questioner3);

        foreach (self::$questionBlock1 as $content) {
            $question = new Question();
            $question
                ->setContent($content);
            $manager->persist($question);

            $questioner1->addQuestion($question);
        }

        foreach (self::$questionBlock2 as $content) {
            $question = new Question();
            $question
                ->setContent($content);
            $manager->persist($question);

            $questioner2->addQuestion($question);
        }

        foreach (self::$questionBlock3 as $content) {
            $question = new Question();
            $question
                ->setContent($content);
            $manager->persist($question);

            $questioner3->addQuestion($question);
        }

        $manager->flush();
    }
}
