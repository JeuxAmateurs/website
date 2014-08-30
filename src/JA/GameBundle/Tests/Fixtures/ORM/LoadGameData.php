<?php

namespace JA\GameBundle\Tests\Fixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadGameData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    static public $games = array();

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $gameClass = $this->container->getParameter('ja_game.game.class');

        $game = new $gameClass();
        $game->setName('My Game');
        $game->addTechnology($this->getReference('tech')); // Tech will auto add the game
        $game->addTechnology($this->getReference('tech2'));
        $manager->persist($game);

        $game2 = new $gameClass();
        $game2->setName('My Second Game');
        $game2->addTechnology($this->getReference('tech2'));
        $manager->persist($game2);

        self::$games = array($game, $game2);

        $manager->flush();

        $this->addReference('game', $game);
        $this->addReference('game2', $game2);
    }

    public function getOrder()
    {
        return 2;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}