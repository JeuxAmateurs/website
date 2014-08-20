<?php

namespace JA\AppBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;

class UserHandler implements UserHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;

    public function __construct(ObjectManager $om, $entityClass)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
    }

    /**
     * {@inheritdoc}
     */
    public function getAll()
    {
        return $this->repository->findAll();
    }

    /**
     * {@inheritdoc}
     */
    public function get($username)
    {
        return $this->repository->findOneBy(array('usernameCanonical' => $username));
    }
}