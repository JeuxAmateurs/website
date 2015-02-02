<?php

namespace JA\AppBundle\Handler;

use JA\AppBundle\Model\GameInterface;

interface GameHandlerInterface
{
    /**
     * Get all games
     *
     * @api
     *
     * @return array
     */
    public function getAll();

    /**
     * Get a Game given the identifier
     *
     * @api
     *
     * @param string $slug
     *
     * @return GameInterface
     */
    public function get($slug);

    /**
     * Create a new Game.
     *
     * @param array|null $parameters
     *
     * @return GameInterface
     */
    public function post($parameters);

    /**
     * Edit a given Game
     * @todo : or create it if it don't exist
     *
     * @param GameInterface $game
     * @param array|null $parameters
     *
     * @return GameInterface
     */
    public function put(GameInterface $game, $parameters);

    /**
     * Partially update a given Game
     *
     * @param GameInterface $game
     * @param array|null $parameters
     *
     * @return GameInterface
     */
    public function patch(GameInterface $game, $parameters);

    /**
     * Delete a given Game
     *
     * @param GameInterface $game
     *
     */
    public function delete(GameInterface $game);
}
