<?php

namespace JA\GameBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

class LoadGameData extends ContainerAware implements FixtureInterface
{
    static public $games = array();

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $gameClass = $this->container->getParameter('ja_game.game.class');

        $game = new $gameClass();
        $game->setName('My Game');
        $manager->persist($game);

        $game2 = new $gameClass();
        $game2->setName('My Second Game');
        $manager->persist($game2);

        self::$games = array($game, $game2);

        $manager->flush();
    }
}