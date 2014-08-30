<?php

namespace JA\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JA\GameBundle\Entity\Game as BaseGame;

/**
 * Game
 *
 * @ORM\Table(name="ja_game")
 * @ORM\Entity(repositoryClass="JA\AppBundle\Entity\GameRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Game extends BaseGame
{
    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="ownedGames")
     * @ORM\JoinTable(name="user_owned_games",
     *  joinColumns={@ORM\JoinColumn(name="user_id", referencedColumnName="id", nullable=false)},
     *  inverseJoinColumns={@ORM\JoinColumn(name="game_id", referencedColumnName="id")},
     * )
     */
    protected $owner;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Set owner
     *
     * @param User $owner
     * @return Game
     */
    public function setOwner(User $owner)
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }
}
