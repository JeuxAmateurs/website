<?php

namespace JA\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserData extends AbstractFixture implements ContainerAwareInterface
{
    static public $users = array();

    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $um = $this->container->get('fos_user.util.user_manipulator');

        $admin = $um->create('SuperAdmin', 'SuperPassword', 'SuperAdmin@SuperWebsite.fr', true, true);
        $dev = $um->create('IAmDev', 'passdev', 'IAmDev@dev.fr', true, false);
        $user = $um->create('Jean-Michel', 'password', 'jean-michel@francis.fr', true, false);

        self::$users = array($admin, $dev, $user);

        $this->setReference('user-admin', $admin);
        $this->setReference('user-dev', $dev);
        $this->setReference('user', $user);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}