<?php

namespace JA\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use JA\GameBundle\Entity\Game;

class LoadGameData implements FixtureInterface
{
    static public $games = array();

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $game = new Game();
        $game->setName('My Game');
        $game->setCreatedAt(new \DateTime('2013-07-31 10:29:00'));
        $game->setUpdatedAt(new \DateTime('2013-08-08 13:37:00'));
        $manager->persist($game);

        $game2 = new Game();
        $game2->setName('My Second Game');
        $game2->setCreatedAt(new \DateTime());
        $game2->setUpdatedAt(new \DateTime());
        $manager->persist($game2);

        self::$games = array($game, $game2);

        $manager->flush();
    }
}