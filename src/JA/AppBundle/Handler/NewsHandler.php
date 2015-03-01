<?php

namespace JA\AppBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use JA\AppBundle\Entity\News;
use JA\AppBundle\Model\UserInterface;

use JA\AppBundle\Form\Type\NewsType;
use JA\AppBundle\Model\NewsInterface;
use JA\AppBundle\Exception\InvalidFormException;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class NewsHandler implements NewsHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;
    private $tokenStorage;

    public function __construct(ObjectManager $om, $entityClass,
                                FormFactoryInterface $formFactory,
                                TokenStorageInterface $tokenStorage)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
        $this->tokenStorage = $tokenStorage;
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
    public function get($id)
    {
        $news = $this->repository->find($id);

        return $news;
    }

    /**
     * {@inheritdoc}
     */
    public function post($parameters)
    {
        $news = $this->createNews();

        $user = $this->tokenStorage->getToken()->getUser();
        if(!($user instanceof UserInterface))
        {
            throw new AccessDeniedHttpException();
        }

        $news->addAuthor($user);
        $response = $this->processForm($news, $parameters, 'POST');

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    public function put(NewsInterface $news, $parameters)
    {
        //@todo check if the user is auth and can modify the news

        return $this->processForm($news, $parameters, 'PUT');
    }

    /**
     * {@inheritdoc}
     */
    public function patch(NewsInterface $news, $parameters)
    {
        //@todo check if the user is auth and can modify the news

        return $this->processForm($news, $parameters, 'PATCH');
    }

    /**
     * {@inheritdoc}
     */
    public function delete(NewsInterface $news)
    {
        $this->om->remove($news);
        $this->om->flush();
    }

    /**
     * Processes the form.
     *
     * @param NewsInterface $news
     * @param array|null    $parameters
     * @param String        $method
     *
     * @return NewsInterface
     *
     * @throws InvalidFormException
     */
    private function processForm(NewsInterface $news, $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new NewsType(), $news, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method); // If the method is PATCH, we don't set missing data as NULL

        if($form->isValid()) // @todo: XML request works with JSON response -> WTF
        {
            $news = $form->getData();

            $this->om->persist($news);
            $this->om->flush();

            return $news;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    /**
     * @return News
     */
    private function createNews()
    {
        return new $this->entityClass();
    }
}
