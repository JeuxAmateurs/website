<?php

namespace JA\AppBundle\DataFixtures\ORM;

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
        $gameClass = $this->container->getParameter('ja_app.game.class');

        $user = $this->getReference('user-dev');

        $game = new $gameClass();
        $game->setName('My Game');
        $game->addTechnology($this->getReference('tech')); // Tech will auto add the game
        $game->addTechnology($this->getReference('tech2'));
        $user->addOwnedGame($game);
        $manager->persist($game);
        $manager->persist($user);

        $game2 = new $gameClass();
        $game2->setName('My Second Game');
        $game2->addTechnology($this->getReference('tech2'));
        $user->addOwnedGame($game2);
        $manager->persist($game2);
        $manager->persist($user);

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

class LoadTechnologyData extends \JA\GameBundle\Tests\Fixtures\ORM\LoadTechnologyData
{
    public function getOrder()
    {
        return 1;
    }
}