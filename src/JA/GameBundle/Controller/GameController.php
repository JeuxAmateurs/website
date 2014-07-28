<?php

namespace JA\GameBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Doctrine\ORM\UnitOfWork;
use Symfony\Component\Form\FormTypeInterface;

use JA\GameBundle\Form\GameType;
use JA\GameBundle\Exception\InvalidFormException;

class GameController extends FOSRestController implements ClassResourceInterface
{
    /**
     * Get all the games. @todo: doc !
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets all games",
     *   output = "JA\GameBundle\Entity\Game",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the game is not found"
     *   }
     * )
     *
     * @Rest\View(templateVar="games")
     *
     * @return array
     *
     * @throws NotFoundHttpException when games not exist
     */
    public function cgetAction()
    {
        if(!($games = $this->container->get('ja_game.game.handler')->getAll())) {
            throw $this->createNotFoundException("The resources were not found.");
        }

        return $games;
    }

    /**
     * Get single Game. @todo: doc !
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
     * @param int $id   the game id
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

        return $game;
    }

    /**
     * Presents the form to use to create a new Game.
     *
     * @ApiDoc(
     *   resource = true,
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Rest\View()
     *
     * @return FormTypeInterface
     */
    public function newAction()
    {
        return $this->createForm(new GameType(), null, array('action' => $this->generateUrl('api_1_post_game')));
    }

    /**
     * Create a new game from submitted data. @todo: doc !
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Create a new Game from data sent",
     *   output = "",
     *   statusCodes = {
     *     201 = "Returned when successful",
     *     204 = "Returned when data already exists",
     *     400 = "Returned when the data sent is not valid"
     *   }
     * )
     *
     * If the template is returned, you have a bad request
     * @Rest\View(
     *      template="JAGameBundle:Game:new.html.twig",
     *      statusCode = Codes::HTTP_BAD_REQUEST
     * )
     *
     * @param Request $request
     *
     * @return FormTypeInterface|View
     */
    public function postAction(Request $request)
    {
        try
        {
            // Game handler create a new Game.
            $newGame = $this->container->get('ja_game.game.handler')->post(
                $request->request->all()
            );

            $routeOptions = array(
                'id' => $newGame->getId()
            );

            $view = $this->routeRedirectView('api_1_get_game', $routeOptions, Codes::HTTP_CREATED);
            $view->setData($newGame); // we send the data to avoid multiple requests

            return $view;
        }
        catch(InvalidFormException $exception)
        {
            return $exception->getForm();
        }
    }
}
