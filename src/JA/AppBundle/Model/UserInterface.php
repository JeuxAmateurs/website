<?php

namespace JA\AppBundle\Model;
use FOS\UserBundle\Model\UserInterface as BaseUserInterface;

use Doctrine\Common\Collections\ArrayCollection;

interface UserInterface extends BaseUserInterface
{
    /**
     * Get biography
     *
     * @return integer
     */
    public function getBiography();

    /**
     * Set biography
     *
     * @param string $biography
     */
    public function setBiography($biography);

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
