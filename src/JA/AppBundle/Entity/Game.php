<?php

namespace JA\AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JA\GameBundle\Entity\Game as BaseGame;

/**
 * Game
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="JA\AppBundle\Entity\GameRepository")
 * @ORM\HasLifecycleCallbacks
 */
class Game extends BaseGame
{
    public function __construct()
    {
        parent::__construct();
    }
}
