<?php

namespace JA\AppBundle\DataFixtures\ORM;

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
        $techClass = $this->container->getParameter('ja_app.technology.class');

        $html = new $techClass();
        $html->setName('HTML');
        $html->setDescription('Markup language for describing a web page.');
        $html->setContent(
            "Hypertext Markup Language"
        );
        $manager->persist($html);

        $css = new $techClass();
        $css->setName('CSS');
        $css->setDescription('Makes your web page prettier.');
        $css->setContent(
            "Cascading Stylesheet"
        );
        $manager->persist($css);

        $sfml = new $techClass();
        $sfml->setName('SFML');
        $sfml->setDescription('Simple and Fast Multimedia Library');
        $sfml->setContent(
            "SFML is multi-media
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
it is also available in many other languages such as Java, Ruby, Python, Go, and more."
        );
        $manager->persist($sfml);

        $cpp = new $techClass();
        $cpp->setName('C++');
        $cpp->setDescription('Fast compiled OO language !');
        $cpp->setContent('I know you TL;DR the last example !');
        $manager->persist($cpp);

        $awesomeTech = new $techClass();
        $awesomeTech->setName('Awesome Tech');
        $awesomeTech->setDescription('An awesome tech for making awesome videogames.');
        $awesomeTech->setContent(
            "Wow very awesome. Such tech."
        );
        $manager->persist($awesomeTech);

        self::$technologies = array($html, $css, $sfml, $cpp, $awesomeTech);

        $manager->flush();

        $this->addReference('html', $html);
        $this->addReference('css', $css);
        $this->addReference('sfml', $sfml);
        $this->addReference('cpp', $cpp);
        $this->addReference('awesome-tech', $awesomeTech);
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