<?php

namespace JA\AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;

class LoadUserData extends AbstractFixture implements OrderedFixtureInterface, ContainerAwareInterface
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
        $user1Team = $um->create('user1-team', 'password1', 'user1@team.fr', true, false);
        $user2Team = $um->create('user2-team', 'password2', 'user2@team.fr', true, false);
        $user3Team = $um->create('user3-team', 'password3', 'user3@team.fr', true, false);
        $brindesable = $um->create('brindesable', 'brindesable', 'brindesable@switchcode.fr', true, false);
        $yorunohikage = $um->create('yorunohikage', 'yorunohikage', 'yorunohikage@switchcode.fr', true, false);

        $dev->setBiography(
            "# My life #
## the beginning ##

First, I was a baby. And then, I grew up ! I am an adult now. I want to do many things. Ok, you understand ? Good.

## the end ##

Oh wait, I'm not done yet ! Here's the things that I want to do :

- to break free
- to enjoy my life
- to stop you in your reading

-------

Haha, fun isn't it ? Now, I want to show you a awesome code :

```c
bool happiness = makeAwesomeThings();
```

We did it !

> I'm starving
> - Somebody very smart

| Day         | Time to work | Time to sleep/do nothing |
| ----------- |:------------:|:------------------------:|
| *Monday*    | 6h           | 18h                      |
| *Tuesday*   | 7h           | 17h                      |
| *Wednesday* | 8h           | 16h                      |
| *Thursday*  | 7h           | 17h                      |
| *Friday*    | 2h           | 22h                      |

Let's finish with a cool image :

![Super description](https://www.lafleur.com/webfolder_download/85ea142142497609e6e21904274aeca8/normal.png)"
        );
        $manager->persist($admin);
        $manager->persist($dev);
        $manager->persist($user);
        $manager->flush();

        self::$users = array($admin, $dev, $user);

        $this->setReference('user-admin', $admin);
        $this->setReference('user-dev', $dev);
        $this->setReference('user', $user);
        $this->setReference('user1-team', $user1Team);
        $this->setReference('user2-team', $user2Team);
        $this->setReference('user3-team', $user3Team);
        $this->setReference('brindesable', $brindesable);
        $this->setReference('yorunohikage', $yorunohikage);
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    function getOrder()
    {
        return 1;
    }
}
