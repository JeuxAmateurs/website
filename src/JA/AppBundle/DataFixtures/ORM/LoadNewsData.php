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
        $news1->setTitle('Sortie de Protal 2');
        $news1->setContent(
"Hélo les amis, Apèrture scayence, oui dou ouate oui love bicose oui canne.
Toudaye, aï ame goingue tou spik in French.

Protal 2 : Un jeu made in China
===============================

Tout le monde sait que l'industrie vidéo-ludique chinoise prospère et réalise de meilleurs chiffres chaque année.
C'est pourquoi, ici à Vlave, nous avons décidé pour notre second épisode de Protal, de distribuer notre jeu sur une
plate-forme dématérialisée : Chteam.

C'est pourquoi j'ai l'immense honneur de vous annoncer la sortie de ce jeu. Vous pourrez le télécharger pour la
modique somme de 0,12€. Et comme ici à Vlave nous aimons la transparence financière, voici les reversements :

- 0.0001€ pour financer les salaires des développeurs
- Le reste ira au PDG de Vlave
"
        );
        $news1->setSources('protal.cn, vlave.com');
        $game->addOwnedNews($news1);
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