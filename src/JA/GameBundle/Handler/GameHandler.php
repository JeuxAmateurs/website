<?php

namespace JA\GameBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;
use JA\GameBundle\Model\GameInterface;

class GameHandler implements GameHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    //private $formFactory;

    public function __construct(ObjectManager $om, $entityClass/*, FormFactoryInterface $formFactory*/)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        //$this->formFactory = $formFactory;
    }

    /**
     * Get a Game given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return GameInterface
     */
    public function get($id)
    {
        return $this->repository->find($id);
    }
}