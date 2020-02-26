<?php

namespace App\DataFixtures;

use App\Entity\Questioner;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class QuestionerFixtures extends Fixture
{
    /**
     * Set of references constants
     */
    public const BOB_REFERENCE = 'Questioner_Bob';
    public const JACK_REFERENCE = 'Questioner_Jack';
    public const JENNY_REFERENCE = 'Questioner_Jenny';

    protected static array $questionersData = [
        [
            'email' => 'bob@example.com',
            'name' => 'Bob',
            'nick' => 'BigBob',
            'reference' => self::BOB_REFERENCE
        ],
        [
            'email' => 'jack@example.com',
            'name' => 'Jack',
            'nick' => 'SmallHit',
            'reference' => self::JACK_REFERENCE
        ],
        [
            'email' => 'jenny@example.com',
            'name' => 'Jenny',
            'nick' => 'NiceTit',
            'reference' => self::JENNY_REFERENCE
        ],
    ];

    /**
     * Creates questioner objects and adds references
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        foreach (self::$questionersData as $questionerData) {
            $questioner = new Questioner();
            $questioner
                ->setEmail($questionerData['email'])
                ->setName($questionerData['name'])
                ->setNick($questionerData['nick']);

            $manager->persist($questioner);

            $this->setReference($questionerData['reference'], $questioner);
        }

        $manager->flush();
    }
}
