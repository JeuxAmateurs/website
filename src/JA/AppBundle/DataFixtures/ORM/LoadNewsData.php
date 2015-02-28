<?php

namespace JA\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadNewsData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
{
    static public $news = array();

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $newsClass = $this->container->getParameter('ja_app.news.class');

        $user = $this->getReference('user-dev');
        $game = $this->getReference('protal');

        $news1 = new $newsClass();
        $news1->setTitle('Protal 2 vient de sortir');
        $news1->setContent('Apèrture scayence, oui dou ouate oui love bicose oui canne.');
        $news1->setSources('portal.com, valve.com');
        //$game->addOwnedNews($news1);
        //$news1->setMedia('');
        //$news1->setComments('');
        //$news1->setAuthorTeam('');
        $manager->persist($news1);
        $manager->persist($game);

        self::$news = array($news1);

        $manager->flush();

        $this->addReference('protal-news', $news1);
    }

    public function getOrder()
    {
        return 4;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}