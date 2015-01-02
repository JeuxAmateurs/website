<?php

namespace JA\AppBundle\Model;
use FOS\UserBundle\Model\UserInterface as BaseUserInterface;

use Doctrine\Common\Collections\ArrayCollection;

interface UserInterface extends BaseUserInterface
{
    /**
     * Add ownedGame
     *
     * @param GameInterface $ownedGame
     * @return $this
     */
    public function addOwnedGame(GameInterface $ownedGame);

    /**
     * Remove one element to ownedGames
     *
     * @param GameInterface $ownedGame
     */
    public function removeOwnedGame(GameInterface $ownedGame);

    /**
     * Get ownedGames
     *
     * @return ArrayCollection
     */
    public function getOwnedGames();
}
