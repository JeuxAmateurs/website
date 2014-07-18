<?php

namespace JA\GameBundle\Controller;

use JA\GameBundle\Entity\Game;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Controller\Annotations as Rest;

use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class GameController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get single Game,
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Game for a given id",
     *   output = "JA\GameBundle\Entity\Game",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the game is not found"
     *   }
     * )
     *
     * @Rest\View(templateVar="game")
     *
     * @param int     $id      the game id
     *
     * @return array
     *
     * @throws NotFoundHttpException when game not exist
     */
    public function getAction($id)
    {
        if(!($game = $this->container->get('ja_game.game.handler')->get($id))) {
            throw $this->createNotFoundException("The resource '". $id ."' was not found.");
        }

        /*$game = new Game();
        $game->setName('mon jeu');
        $game->setCreatedAt(new \DateTime());
        $game->setUpdatedAt(new \DateTime());*/

        return $game;
    }
}
