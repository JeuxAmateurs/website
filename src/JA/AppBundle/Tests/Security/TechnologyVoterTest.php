<?php

namespace JA\AppBundle\Tests\Security;

use JA\AppBundle\Security\TechnologyVoter;
use Liip\FunctionalTestBundle\Test\WebTestCase;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\Role\RoleHierarchy;

class TechnologyVoterTest extends WebTestCase
{
    /**
     * @var TechnologyVoter $voter
     */
    protected $voter;

    /** @var TechnologyVoter $class */
    static $voterClass;

    /** @var string $entityClass */
    static $entityClass = 'JA\AppBundle\Entity\Technology';

    /**
     * @var RoleHierarchy $roleHierarchy
     */
    private $roleHierarchy;

    public function setUp()
    {
        $this->roleHierarchy = $this->getContainer()->get('security.role_hierarchy');

        $this->createTechnologyVoter($this->roleHierarchy);
        self::$voterClass = get_class($this->voter);
    }

    public function testAnonymousAccess()
    {
        $token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $token->expects($this->any())
            ->method('getUser')
            ->willReturn('anonymous'); // User is an anonymous, so the token returns a string

        $object = $this->getEntity($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var TechnologyVoter $class */
        $class = self::$voterClass;

        // An anonymous user can't create
        $this->assertAccess($class::CREATE, $class::ACCESS_DENIED, $token, self::$entityClass, 'Anonymous user must NOT be able to create a technology !');

        // An anonymous user can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'Anonymous user must be able to view a technology !');

        // An anonymous user can't edit
        $this->assertAccess($class::EDIT, $class::ACCESS_DENIED, $token, $object, 'Anonymous user must NOT be able to edit a technology !');

        // An anonymous user can't delete
        $this->assertAccess($class::DELETE, $class::ACCESS_DENIED, $token, $object, 'Anonymous user must NOT be able to delete any technology !');
    }

    public function testUserBannedAccess()
    {
        $token = $this->getToken(array('ROLE_BANNED'));
        $object = $this->getEntity($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var TechnologyVoter $class */
        $class = self::$voterClass;

        // A banned user can't create
        $this->assertAccess($class::CREATE, $class::ACCESS_DENIED, $token, self::$entityClass, 'User banned must NOT be able to create a technology !');

        // A banned user can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'User banned must be able to view a technology !');

        // A banned user can't edit
        $this->assertAccess($class::EDIT, $class::ACCESS_DENIED, $token, $object, 'User banned must NOT be able to edit a technology !');

        // A banned user can't delete
        $this->assertAccess($class::DELETE, $class::ACCESS_DENIED, $token, $object, 'User banned must NOT be able to delete any technology !');
    }

    public function testUserAccess()
    {
        $token = $this->getToken(array('ROLE_USER'));

        $object = $this->getEntity($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var TechnologyVoter $class */
        $class = self::$voterClass;

        // A user can create
        $this->assertAccess($class::CREATE, $class::ACCESS_GRANTED, $token, self::$entityClass, 'User must be able to create a technology !');

        // A user can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'User must be able to view a technology !');

        // A user can edit any Game
        $this->assertAccess($class::EDIT, $class::ACCESS_GRANTED, $token, $object, 'User must be able to edit a technology !');

        // A user can't delete
        $this->assertAccess($class::DELETE, $class::ACCESS_DENIED, $token, $object, 'User must NOT be able to delete any technology !');
    }

    public function testModeratorAccess()
    {
        $token = $this->getToken(array('ROLE_MODERATOR'));

        $object = $this->getEntity($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var TechnologyVoter $class */
        $class = self::$voterClass;

        // A moderator can create
        $this->assertAccess($class::CREATE, $class::ACCESS_GRANTED, $token, self::$entityClass, 'Moderator must be able to create a technology !');

        // A moderator can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'Moderator must be able to view a technology !');

        // A moderator can edit any Game
        $this->assertAccess($class::EDIT, $class::ACCESS_GRANTED, $token, $object, 'Moderator must be able to edit a technology !');

        // A moderator can delete
        $this->assertAccess($class::DELETE, $class::ACCESS_GRANTED, $token, $object, 'Moderator must be able to delete a technology !');
    }

    public function testAdminAccess()
    {
        $token = $this->getToken(array('ROLE_ADMIN'));

        $object = $this->getEntity($this->getMock('\JA\AppBundle\Entity\User'));

        /** @var TechnologyVoter $class */
        $class = self::$voterClass;

        // An admin can create
        $this->assertAccess($class::CREATE, $class::ACCESS_GRANTED, $token, self::$entityClass, 'Admin must be able to create a technology !');

        // An admin can view
        $this->assertAccess($class::VIEW, $class::ACCESS_GRANTED, $token, $object, 'Admin must be able to view a technology !');

        // An admin can edit
        $this->assertAccess($class::EDIT, $class::ACCESS_GRANTED, $token, $object, 'Admin must be able to edit a technology !');

        // An admin can delete
        $this->assertAccess($class::DELETE, $class::ACCESS_GRANTED, $token, $object, 'Admin must be able to delete a technology !');
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
    protected function createTechnologyVoter(RoleHierarchy $roleHierarchy)
    {
        $this->voter = new TechnologyVoter($roleHierarchy);
    }

    /**
     * Returns a technology
     *
     * @param $user
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getEntity($user)
    {
        $object = $this->getMock(self::$entityClass);

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
