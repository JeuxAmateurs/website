<?php

namespace JA\AppBundle\Tests\Handler;

use JA\AppBundle\Entity\Game;
use JA\AppBundle\Handler\GameHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameHandlerTest extends WebTestCase
{
    /** @var GameHandler */
    protected $gameHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $formFactory;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $token;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $tokenStorage;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $user;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $authorizationChecker;

    const GAME_CLASS = 'JA\AppBundle\Tests\Handler\DummyGame';

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');
        $this->formFactory = $this->getMock('Symfony\Component\Form\FormFactoryInterface');
        $this->token = $this->getMock('Symfony\Component\Security\Core\Authentication\Token\TokenInterface');
        $this->tokenStorage = $this->getMockBuilder('Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->user = $this->getMock('JA\AppBundle\Entity\User');
        $this->authorizationChecker = $this->getMockBuilder('Symfony\Component\Security\Core\Authorization\AuthorizationChecker')
            ->disableOriginalConstructor()
            ->getMock();

        $this->om->expects($this->any())
            ->method('getRepository')
            ->with($this->equalTo(static::GAME_CLASS))
            ->will($this->returnValue($this->repository));
        $this->om->expects($this->any())
            ->method('getClassMetadata')
            ->with($this->equalTo(static::GAME_CLASS))
            ->will($this->returnValue($class));
        $class->expects($this->any())
            ->method('getName')
            ->will($this->returnValue(static::GAME_CLASS));

        // We expect to call the Security Token to get the current user
        $this->tokenStorage->expects($this->any())
            ->method('getToken')
            ->will($this->returnValue($this->token));

        $this->token->expects($this->any())
            ->method('getUser')
            ->will($this->returnValue($this->user));

        // The authorization checker will be called to verify permissions
        $this->authorizationChecker->expects($this->any())
            ->method('isGranted')
            ->will($this->returnValue(true));

        $this->gameHandler = $this->createGameHandler($this->om, static::GAME_CLASS, $this->formFactory, $this->tokenStorage, $this->authorizationChecker);
    }

    public function testGetAll()
    {
        $games = array();
        $this->repository->expects($this->once())
            ->method('findAll')
            ->will($this->returnValue($games)); // GameCollection

        $this->gameHandler->getAll(); // call the get.
    }

    public function testGet()
    {
        $slug = 'test'; // value is not important here
        $game = $this->getGame();
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(array('slug' => $slug)))
            ->will($this->returnValue($game));

        $this->gameHandler->get($slug); // call the get.
    }

    public function testPost()
    {
        $parameters = array('name' => 'Yolo');

        $game = $this->getGame();
        $game->setName($parameters['name']);

        $form = $this->getMock('JA\AppBundle\Tests\Handler\FormInterface');
        $form->expects($this->once())
            ->method('submit')
            ->with($this->anything());
        $form->expects($this->once())
            ->method('isValid')
            ->will($this->returnValue(true));
        $form->expects($this->once())
            ->method('getData')
            ->will($this->returnValue($game));

        $this->formFactory->expects($this->once())
            ->method('create')
            ->will($this->returnValue($form));

        $this->gameHandler = $this->createGameHandler($this->om, static::GAME_CLASS, $this->formFactory, $this->tokenStorage, $this->authorizationChecker);
        $gameFromTest = $this->gameHandler->post($parameters);

        $this->assertEquals($game, $gameFromTest);
    }

    protected function createGameHandler($objectManager, $pageClass, $formFactory, $tokenStorage, $authorizationChecker)
    {
        return new GameHandler($objectManager, $pageClass, $formFactory, $tokenStorage, $authorizationChecker);
    }

    protected function getGame()
    {
        $gameClass = static::GAME_CLASS;

        return new $gameClass();
    }
}

class DummyGame extends Game {}

interface FormInterface extends \Iterator, \Symfony\Component\Form\FormInterface {}