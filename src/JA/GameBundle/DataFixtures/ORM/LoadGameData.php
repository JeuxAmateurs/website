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
        $manager->persist($game);

        $game2 = new Game();
        $game2->setName('My Second Game');
        $manager->persist($game2);

        self::$games = array($game, $game2);

        $manager->flush();
    }
}