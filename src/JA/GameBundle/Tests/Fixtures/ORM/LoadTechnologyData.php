<?php

namespace JA\GameBundle\Tests\Fixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadTechnologyData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    static public $technologies = array();

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $techClass = $this->container->getParameter('ja_game.technology.class');

        $tech = new $techClass();
        $tech->setName('SFML');
        $tech->setDescription('Simple and Fast Multimedia Library');
        $tech->setContent(
            'SFML is multi-media
            ====================
            SFML provides a simple interface to the various components of your PC, to ease the development of games
            and multimedia applications. It is composed of five modules: system, window, graphics, audio and network.

            SFML is multi-platform
            ======================
            With SFML, your application can compile and run out of the box on the most common operating systems:
            Windows, Linux, Mac OS X and soon Android & iOS.

            SFML is multi-language
            ======================
            SFML has official bindings for the C and .Net languages. And thanks to its active community,
            it is also available in many other languages such as Java, Ruby, Python, Go, and more.
        ');
        $manager->persist($tech);

        $tech2 = new $techClass();
        $tech2->setName('C++');
        $tech2->setDescription('Fast compiled OO language !');
        $tech2->setContent('I know you TL;DR the last example !');
        $manager->persist($tech2);

        self::$technologies = array($tech, $tech2);

        $manager->flush();

        $this->addReference('tech', $tech);
        $this->addReference('tech2', $tech2);
    }

    public function getOrder()
    {
        return 1;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}