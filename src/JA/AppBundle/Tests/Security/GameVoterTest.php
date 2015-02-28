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
    protected $gameVoter;

    /**
     * @var RoleHierarchy $roleHierarchy
     */
    private $roleHierarchy;

    public function setUp()
    {
        $this->roleHierarchy = $this->getContainer()->get('security.role_hierarchy');

        $this->createGameVoter($this->roleHierarchy);
    }

    public function testAnonymousAccess()
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())
            ->method('getUser')
            ->willReturn('anonymous'); // User is an anonymous, so the token returns a string

        $object = $this->getGame($this->getMock('\JA\AppBundle\Entity\User'));

        // An anonymous user can create a Game
        $this->assertAccess(GameVoter::CREATE, GameVoter::ACCESS_DENIED, $token, null, 'Anonymous user must NOT be able to create a game !');

        // An anonymous user can view a Game
        $this->assertAccess(GameVoter::VIEW, GameVoter::ACCESS_GRANTED, $token, $object, 'Anonymous user must be able to view a game !');

        // An anonymous user can edit any Game
        $this->assertAccess(GameVoter::EDIT, GameVoter::ACCESS_DENIED, $token, $object, 'Anonymous user must NOT be able to edit a game !');

        // An anonymous user can delete a Game
        $this->assertAccess(GameVoter::DELETE, GameVoter::ACCESS_DENIED, $token, $object, 'Anonymous user must NOT be able to delete any game !');
    }

    public function testUserBannedAccess()
    {
        $token = $this->getToken(array('ROLE_BANNED'));
        $object = $this->getGame($this->getMock('\JA\AppBundle\Entity\User'));

        // A banned user can create a Game
        $this->assertAccess(GameVoter::CREATE, GameVoter::ACCESS_GRANTED, $token, null, 'User banned must be able to create a game !');

        // A banned user can view a Game
        $this->assertAccess(GameVoter::VIEW, GameVoter::ACCESS_GRANTED, $token, $object, 'User banned must be able to view a game !');

        // A banned user can edit any Game
        $this->assertAccess(GameVoter::EDIT, GameVoter::ACCESS_DENIED, $token, $object, 'User banned must NOT be able to edit a game !');

        // A banned user can delete a Game
        $this->assertAccess(GameVoter::DELETE, GameVoter::ACCESS_DENIED, $token, $object, 'User banned must NOT be able to delete any game !');
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

        // A user can create a Game
        $this->assertAccess(GameVoter::CREATE, GameVoter::ACCESS_GRANTED, $token, null, 'User must be able to create a game !');

        // A user can view a Game
        $this->assertAccess(GameVoter::VIEW, GameVoter::ACCESS_GRANTED, $token, $object, 'User must be able to view a game !');

        // A user can edit any Game
        $this->assertAccess(GameVoter::EDIT, GameVoter::ACCESS_DENIED, $token, $object, 'User must NOT be able to edit a game !');

        // A user can delete a Game
        $this->assertAccess(GameVoter::DELETE, GameVoter::ACCESS_DENIED, $token, $object, 'User must NOT be able to delete any game !');
    }

    public function testOwnerAccess()
    {
        $token = $this->getToken(array('ROLE_USER'));

        $object = $this->getGame($token->getUser()); // User is the game's owner

        // An owner can create a Game
        $this->assertAccess(GameVoter::CREATE, GameVoter::ACCESS_GRANTED, $token, null, 'Owner must be able to create a game !');

        // An owner can view his Game
        $this->assertAccess(GameVoter::VIEW, GameVoter::ACCESS_GRANTED, $token, $object, 'Owner must be able to view a game !');

        // An owner can edit his Game
        $this->assertAccess(GameVoter::EDIT, GameVoter::ACCESS_GRANTED, $token, $object, 'Owner must be able to edit his game !');

        // An owner can delete his Game
        $this->assertAccess(GameVoter::DELETE, GameVoter::ACCESS_GRANTED, $token, $object, 'Owner must be able to delete his game !');
    }

    public function testModeratorAccess()
    {
        $token = $this->getToken(array('ROLE_MODERATOR'));

        $object = $this->getGame($this->getMock('\JA\AppBundle\Entity\User'));

        // A moderator can create a Game
        $this->assertAccess(GameVoter::CREATE, GameVoter::ACCESS_GRANTED, $token, null, 'Moderator must be able to create a game !');

        // A moderator can view a Game
        $this->assertAccess(GameVoter::VIEW, GameVoter::ACCESS_GRANTED, $token, $object, 'Moderator must be able to view a game !');

        // A moderator can edit any Game
        $this->assertAccess(GameVoter::EDIT, GameVoter::ACCESS_GRANTED, $token, $object, 'Moderator must be able to edit a game !');

        // A moderator can delete a Game
        $this->assertAccess(GameVoter::DELETE, GameVoter::ACCESS_DENIED, $token, $object, 'Moderator must NOT be able to delete a game !');
    }

    public function testAdminAccess()
    {
        $token = $this->getToken(array('ROLE_ADMIN'));

        $object = $this->getGame($this->getMock('\JA\AppBundle\Entity\User'));

        // An admin can create a Game
        $this->assertAccess(GameVoter::CREATE, GameVoter::ACCESS_GRANTED, $token, null, 'Admin must be able to create a game !');

        // An admin can view a Game
        $this->assertAccess(GameVoter::VIEW, GameVoter::ACCESS_GRANTED, $token, $object, 'Admin must be able to view a game !');

        // An admin can edit any Game
        $this->assertAccess(GameVoter::EDIT, GameVoter::ACCESS_GRANTED, $token, $object, 'Admin must be able to edit a game !');

        // An admin can delete a Game
        $this->assertAccess(GameVoter::DELETE, GameVoter::ACCESS_GRANTED, $token, $object, 'Admin must be able to delete a game !');
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
            $this->gameVoter->vote($token, $object, array($attribute)),
            $access,
            $message
        );
    }

    /**
     * @param RoleHierarchy $roleHierarchy
     */
    protected function createGameVoter(RoleHierarchy $roleHierarchy)
    {
        $this->gameVoter = new GameVoter($roleHierarchy);
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
            ->willReturn($token->getRoles());
        $user->expects($this->any())
            ->method('getId')
            ->willReturn(1337);

        $token->expects($this->any())
            ->method('getUser')
            ->willReturn($user);

        return $token;
    }
}
