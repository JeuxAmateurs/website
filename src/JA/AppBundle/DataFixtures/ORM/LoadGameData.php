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
        $brindesable = $this->getReference('brindesable');
        $yorunohikage = $this->getReference('yorunohikage');

        $game = new $gameClass();
        $game->setName('My Game');
        $game->addTechnology($this->getReference('html')); // Tech will auto add the game
        $game->addTechnology($this->getReference('css'));
        $brindesable->addOwnedGame($game);
        $user->addOwnedGame($game);
        $manager->persist($game);


        $game2 = new $gameClass();
        $game2->setName('My Second Game');
        $game2->addTechnology($this->getReference('html'));
        $yorunohikage->addFavoriteGame($game2);
        $user->addOwnedGame($game2);
        $manager->persist($game2);

        $portal3 = new $gameClass();
        $portal3->setName('Portal 3');
        $portal3->addTechnology($this->getReference('cpp'));
        $portal3->addTechnology($this->getReference('awesome-tech'));
        $yorunohikage->addOwnedGame($portal3);
        $manager->persist($portal3);

        $HRMP = new $gameClass();
        $HRMP->setName('8BitRobotMusicParty');
        $HRMP->addTechnology($this->getReference('html'));
        $user->addOwnedGame($HRMP);
        $user->addFavoriteGame($HRMP);
        $manager->persist($HRMP);

        $manager->persist($user);
        $manager->persist($brindesable);
        $manager->persist($yorunohikage);


        self::$games = array($game, $game2, $portal3, $HRMP);

        $manager->flush();

        $this->addReference('game', $game);
        $this->addReference('game2', $game2);
        $this->addReference('8RMP', $HRMP);
        $this->addReference('portal3', $portal3);
    }

    public function getOrder()
    {
        return 3;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}