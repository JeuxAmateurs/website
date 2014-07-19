<?php

namespace JA\GameBundle\Tests\Handler;

use JA\GameBundle\Entity\Game;
use JA\GameBundle\Handler\GameHandler;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class GameHandlerTest extends WebTestCase
{
    /** @var GameHandler */
    protected $gameHandler;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $om;
    /** @var \PHPUnit_Framework_MockObject_MockObject */
    protected $repository;

    const GAME_CLASS = 'JA\GameBundle\Tests\Handler\DummyGame';

    public function setUp()
    {
        if (!interface_exists('Doctrine\Common\Persistence\ObjectManager')) {
            $this->markTestSkipped('Doctrine Common has to be installed for this test to run.');
        }

        $class = $this->getMock('Doctrine\Common\Persistence\Mapping\ClassMetadata');
        $this->om = $this->getMock('Doctrine\Common\Persistence\ObjectManager');
        $this->repository = $this->getMock('Doctrine\Common\Persistence\ObjectRepository');

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

        $this->gameHandler = $this->createGameHandler($this->om, static::GAME_CLASS);
    }

    public function testGet()
    {
        $id = 1;
        $game = $this->getGame();
        $this->repository->expects($this->once())
            ->method('find')
            ->with($this->equalTo($id))
            ->will($this->returnValue($game));

        $this->gameHandler->get($id); // call the get.
    }

    protected function createGameHandler($objectManager, $pageClass)
    {
        return new GameHandler($objectManager, $pageClass);
    }

    protected function getGame()
    {
        $gameClass = static::GAME_CLASS;

        return new $gameClass();
    }
}

class DummyGame extends Game {}