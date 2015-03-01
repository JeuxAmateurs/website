<?php

namespace JA\AppBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use JA\AppBundle\Exception\InvalidFormException;
use JA\AppBundle\Form\Type\TechnologyFormType;
use JA\AppBundle\Model\TechnologyInterface;
use JA\AppBundle\Model\UserInterface;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationChecker;

class TechnologyHandler implements TechnologyHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $tokenStorage;

    public function __construct(ObjectManager $om, $entityClass,
                                FormFactory $formFactory, TokenStorageInterface $tokenStorage, AuthorizationChecker $authorizationChecker)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($entityClass);
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
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
    public function get($slug)
    {
        $technology = $this->repository->findOneBy(array('slug' => $slug));

        return $technology;
    }

    /**
     * {@inheritdoc}
     */
    public function post($parameters)
    {
        $technology = $this->createTechnology();

        $user = $this->tokenStorage->getToken()->getUser();
        if(!($user instanceof UserInterface) && !$this->authorizationChecker->isGranted('create'))
        {
            throw new AccessDeniedHttpException();
        }

        $response = $this->processForm($technology, $parameters, 'POST');

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function put(TechnologyInterface $technology, $parameters)
    {
        // check for edit access
        if(!$this->authorizationChecker->isGranted('edit', $technology))
        {
            throw new AccessDeniedHttpException();
        }

        return $this->processForm($technology, $parameters, 'PUT');
    }

    /**
     * {@inheritdoc}
     */
    public function patch(TechnologyInterface $technology, $parameters)
    {
        // TODO: Don't patch like an idiot

        // check for edit access
        if(!$this->authorizationChecker->isGranted('edit', $technology))
        {
            throw new AccessDeniedHttpException();
        }

        return $this->processForm($technology, $parameters, 'PATCH');
    }

    /**
     * {@inheritdoc}
     */
    public function delete(TechnologyInterface $technology)
    {
        // check for edit access
        if(!$this->authorizationChecker->isGranted('delete', $technology))
        {
            throw new AccessDeniedHttpException();
        }

        $this->om->remove($technology);
        $this->om->flush();
    }

    /**
     * Processes the form.
     *
     * @param TechnologyInterface $technology
     * @param array|null    $parameters
     * @param String        $method
     *
     * @return TechnologyInterface
     *
     * @throws InvalidFormException
     */
    private function processForm(TechnologyInterface $technology, $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new TechnologyFormType(), $technology, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method); // If the method is PATCH, we don't set missing data as NULL

        if($form->isValid())
        {
            $technology = $form->getData();

            $this->om->persist($technology);
            $this->om->flush();

            return $technology;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    public function createTechnology()
    {
        return new $this->entityClass;
    }
}