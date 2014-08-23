<?php

namespace JA\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAware;

use JA\AppBundle\Entity\User;

class LoadUserData extends ContainerAware implements FixtureInterface
{
    static public $users = array();

    /**
    * {@inheritDoc}
    */
    public function load(ObjectManager $manager)
    {
        $um = $this->container->get('fos_user.util.user_manipulator');

        $admin = $um->create('SuperAdmin', 'SuperPassword', 'SuperAdmin@SuperWebsite.fr', true, true);
        $user = $um->create('Jean-Michel', 'password', 'jean-michel@francis.fr', true, false);

        self::$users = array($admin, $user);
    }
}