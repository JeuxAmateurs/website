<?php

namespace JA\AppBundle\Handler;

use JA\AppBundle\Model\UserInterface;

interface UserHandlerInterface
{
    /**
     * Get all users
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
     * @param string $username
     *
     * @return UserInterface
     */
    public function get($username);
}
