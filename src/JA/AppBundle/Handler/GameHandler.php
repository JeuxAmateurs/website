<?php

namespace JA\AppBundle\Handler;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Form\FormFactoryInterface;

use JA\AppBundle\Form\GameType;
use JA\AppBundle\Model\GameInterface;
use JA\AppBundle\Exception\InvalidFormException;

class GameHandler implements GameHandlerInterface
{
    private $om;
    private $entityClass;
    private $repository;
    private $formFactory;

    public function __construct(ObjectManager $om, $entityClass, FormFactoryInterface $formFactory)
    {
        $this->om = $om;
        $this->entityClass = $entityClass;
        $this->repository = $this->om->getRepository($this->entityClass);
        $this->formFactory = $formFactory;
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
        return $this->repository->findOneBy(array('slug' => $slug));
    }

    /**
     * {@inheritdoc}
     */
    public function post($parameters)
    {
        $game = $this->createGame();

        return $this->processForm($game, $parameters, 'POST');
    }

    /**
     * {@inheritdoc}
     */
    public function put(GameInterface $game, $parameters)
    {
        return $this->processForm($game, $parameters, 'PUT');
    }

    /**
     * {@inheritdoc}
     */
    public function patch(GameInterface $game, $parameters)
    {
        return $this->processForm($game, $parameters, 'PATCH');
    }

    /**
     * {@inheritdoc}
     */
    public function delete(GameInterface $game)
    {
        $this->om->remove($game);
        $this->om->flush();
    }

    /**
     * Processes the form.
     *
     * @param GameInterface $game
     * @param array|null    $parameters
     * @param String        $method
     *
     * @return GameInterface
     *
     * @throws InvalidFormException
     */
    private function processForm(GameInterface $game, $parameters, $method = "PUT")
    {
        $form = $this->formFactory->create(new GameType(), $game, array('method' => $method));
        $form->submit($parameters, 'PATCH' !== $method); // If the method is PATCH, we don't set missing data as NULL

        if($form->isValid()) // @todo: XML request works with JSON response -> WTF
        {
            $game = $form->getData();

            $this->om->persist($game);
            $this->om->flush($game);

            return $game;
        }

        throw new InvalidFormException('Invalid submitted data', $form);
    }

    private function createGame()
    {
        return new $this->entityClass();
    }
}