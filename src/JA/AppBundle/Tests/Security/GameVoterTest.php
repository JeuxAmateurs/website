<?php

namespace JA\AppBundle\Tests\Security;

use JA\AppBundle\Security\GameVoter;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class GameVoterTest extends WebTestCase
{
    /**
     * @var GameVoter $gameVoter
     */
    protected $voter;

    /** @var object $voterClass */
    private static $voterClass;

    /** @var string $entityClass */
    protected static $entityClass = 'JA\AppBundle\Entity\Game';

    /**
     * @var RoleHierarchy $roleHierarchy
     */
    private $roleHierarchy;

    public function setUp()
    {
        $this->roleHierarchy = $this->getContainer()->get('security.role_hierarchy');

        $this->createGameVoter($this->roleHierarchy);
        self::$voterClass = get_class($this->voter);
    }

    public function testAnonymousAccess()
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())
            ->method('getUser')
            ->willReturn('anonymous'); // User is an anonymous, so the token returns a string

        $object = $this->getGame($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var GameVoter $class */
        $class = self::$voterClass;

        // An anonymous user can create
        $this->assertAccess($class::CREATE, $class::ACCESS_DENIED, $token, self::$entityClass, 'Anonymous user must NOT be able to create a game !');

        // An anonymous user can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'Anonymous user must be able to view a game !');

        // An anonymous user can edit
        $this->assertAccess($class::EDIT, $class::ACCESS_DENIED, $token, $object, 'Anonymous user must NOT be able to edit a game !');

        // An anonymous user can delete
        $this->assertAccess($class::DELETE, $class::ACCESS_DENIED, $token, $object, 'Anonymous user must NOT be able to delete any game !');
    }

    public function testUserBannedAccess()
    {
        $token = $this->getToken(array('ROLE_BANNED'));
        $object = $this->getGame($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var GameVoter $class */
        $class = self::$voterClass;

        // A banned user can't create
        $this->assertAccess($class::CREATE, $class::ACCESS_DENIED, $token, self::$entityClass, 'User banned must NOT be able to create a game !');

        // A banned user can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'User banned must be able to view a game !');

        // A banned user can edit
        $this->assertAccess($class::EDIT, $class::ACCESS_DENIED, $token, $object, 'User banned must NOT be able to edit a game !');

        // A banned user can delete
        $this->assertAccess($class::DELETE, $class::ACCESS_DENIED, $token, $object, 'User banned must NOT be able to delete any game !');
    }

    public function testUserAccess()
    {
        $token = $this->getToken(array('ROLE_USER'));

        // The game must be owned by some user
        $newUser = $this->getMock('JA\AppBundle\Entity\User');
        $newUser->expects($this->any())
            ->method('getId')
            ->willReturn(42);

        $object = $this->getGame($newUser);

        /** @var GameVoter $class */
        $class = self::$voterClass;

        // A user can create
        $this->assertAccess($class::CREATE, $class::ACCESS_GRANTED, $token, self::$entityClass, 'User must be able to create a game !');

        // A user can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'User must be able to view a game !');

        // A user can edit
        $this->assertAccess($class::EDIT, $class::ACCESS_DENIED, $token, $object, 'User must NOT be able to edit a game !');

        // A user can delete
        $this->assertAccess($class::DELETE, $class::ACCESS_DENIED, $token, $object, 'User must NOT be able to delete any game !');
    }

    public function testOwnerAccess()
    {
        $token = $this->getToken(array('ROLE_USER'));

        $object = $this->getGame($token->getUser()); // User is the game's owner

        /** @var GameVoter $class */
        $class = self::$voterClass;

        // An owner can create
        $this->assertAccess($class::CREATE, $class::ACCESS_GRANTED, $token, self::$entityClass, 'Owner must be able to create a game !');

        // An owner can view his Game
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'Owner must be able to view a game !');

        // An owner can edit his Game
        $this->assertAccess($class::EDIT, $class::ACCESS_GRANTED, $token, $object, 'Owner must be able to edit his game !');

        // An owner can delete his Game
        $this->assertAccess($class::DELETE, $class::ACCESS_GRANTED, $token, $object, 'Owner must be able to delete his game !');
    }

    public function testModeratorAccess()
    {
        $token = $this->getToken(array('ROLE_MODERATOR'));

        $object = $this->getGame($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var GameVoter $class */
        $class = self::$voterClass;

        // A moderator can create
        $this->assertAccess($class::CREATE, $class::ACCESS_GRANTED, $token, self::$entityClass, 'Moderator must be able to create a game !');

        // A moderator can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'Moderator must be able to view a game !');

        // A moderator can edit
        $this->assertAccess($class::EDIT, $class::ACCESS_GRANTED, $token, $object, 'Moderator must be able to edit a game !');

        // A moderator can delete
        $this->assertAccess($class::DELETE, $class::ACCESS_DENIED, $token, $object, 'Moderator must NOT be able to delete a game !');
    }

    public function testAdminAccess()
    {
        $token = $this->getToken(array('ROLE_ADMIN'));

        $object = $this->getGame($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var GameVoter $class */
        $class = self::$voterClass;

        // An admin can create
        $this->assertAccess($class::CREATE, $class::ACCESS_GRANTED, $token, self::$entityClass, 'Admin must be able to create a game !');

        // An admin can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'Admin must be able to view a game !');

        // An admin can edit
        $this->assertAccess($class::EDIT, $class::ACCESS_GRANTED, $token, $object, 'Admin must be able to edit a game !');

        // An admin can delete
        $this->assertAccess($class::DELETE, $class::ACCESS_GRANTED, $token, $object, 'Admin must be able to delete a game !');
    }

    /**
     * Assert equals between expected and what the voter returns
     *
     * @param string $attribute
     * @param $access
     * @param object $token
     * @param object|null $object
     * @param string|null $message
     */
    protected function assertAccess($attribute, $access, $token, $object = null, $message = null)
    {
        $this->assertEquals(
            $access,
            $this->voter->vote($token, $object, array($attribute)),
            $message
        );
    }

    /**
     * @param RoleHierarchy $roleHierarchy
     */
    protected function createGameVoter(RoleHierarchy $roleHierarchy)
    {
        $this->voter = new GameVoter($roleHierarchy);
    }

    /**
     * Returns a game with its owner
     *
     * @param $user
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getGame($user)
    {
        $object = $this->getMock('JA\AppBundle\Entity\Game');
        $object->expects($this->any())
            ->method('getOwner')
            ->willReturn($user);

        return $object;
    }

    /**
     * Returns token user with roles given
     *
     * @param array $roles
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getToken(array $roles = array())
    {
        // /!\ $user->getRoles() != $token->getRoles() /!\
        //     return string[]   != return RoleInterface[]

        $userRoles = array_map(function($role) {
            return new Role($role);
        }, $roles);

        $user = $this->getMock('JA\AppBundle\Entity\User');
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');

        $token->expects($this->any())
            ->method('getRoles')
            ->willReturn($userRoles);

        $user->expects($this->any())
            ->method('getRoles')
            ->willReturn($roles);
        $user->expects($this->any())
            ->method('getId')
            ->willReturn(1337);

        $token->expects($this->any())
            ->method('getUser')
            ->willReturn($user);

        return $token;
    }
}
