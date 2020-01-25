<?php

namespace App\DataFixtures;

use App\Entity\ForbiddenWord;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AnswerFixtures extends Fixture
{
    /**
     * Forbidden words in answer
     * @var array
     */
    public static $forbiddenWords = [
        'fuck',
        'motherfucker',
        'pussy',
        'shit',
        'bitch',
        'whore',
        'slut',
        'cocksucker',
        'anal',
        'asshole',
        'ass',
    ];

    public function load(ObjectManager $manager)
    {
        foreach (self::$forbiddenWords as $forbiddenWord) {
            $word = new ForbiddenWord();
            $word->setName($forbiddenWord);

            $manager->persist($word);
        }

        $manager->flush();
    }
}
