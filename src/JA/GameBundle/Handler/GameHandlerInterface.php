<?php

namespace JA\GameBundle\Handler;

use JA\GameBundle\Model\GameInterface;

interface GameHandlerInterface
{
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
}