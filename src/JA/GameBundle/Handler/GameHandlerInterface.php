<?php

namespace JA\GameBundle\Handler;

use JA\GameBundle\Model\GameInterface;

interface GameHandlerInterface
{
    /**
     * Get all games
     *
     * @api
     *
     * @return GameInterface
     */
    public function getAll();

    /**
     * Get a Game given the identifier
     *
     * @api
     *
     * @param mixed $id
     *
     * @return GameInterface
     */
    public function get($id);

    /**
     * Create a new Game.
     *
     * @param array $parameters
     *
     * @return GameInterface
     */
    public function post(array $parameters);

    /**
     * Edit a given Game
     * @todo : or create it if it don't exist
     *
     * @param GameInterface $game
     * @param array $parameters
     *
     * @return GameInterface
     */
    public function put(GameInterface $game, array $parameters);

    /**
     * Partially update a given Game
     *
     * @param GameInterface $game
     * @param array $parameters
     *
     * @return GameInterface
     */
    public function patch(GameInterface $game, array $parameters);
}