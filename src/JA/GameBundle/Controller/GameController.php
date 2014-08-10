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
     * Empty list if there's no games
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets all games",
     *   output = "JA\GameBundle\Entity\Game",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Rest\View(templateVar="games")
     *
     * @return array
     *
     */
    public function cgetAction()
    {
        $games = $this->container->get('ja_game.game.handler')->getAll();

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
     * @param string $slug   the game slug
     *
     * @return array
     *
     * @throws NotFoundHttpException when game not exist
     */
    public function getAction($slug)
    {
        if(!($game = $this->container->get('ja_game.game.handler')->get($slug))) {
            throw $this->createNotFoundException("The resource '". $slug ."' was not found.");
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
     *     204 = "Data already exists",
     *     400 = "The data sent is not valid",
     *     422 = "The game data sent contains errors"
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
                'slug' => $newGame->getSlug()
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

    /**
     * Edit or create a game from submitted data. @todo: doc !
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Edit or create a new Game from data sent",
     *   output = "",
     *   statusCodes = {
     *     201 = "Returned when the data doesn't exist already",
     *     204 = "Returned when successful",
     *     400 = "The data sent is not valid",
     *     422 = "The game data sent contains errors"
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
     * @param string $slug The slug to identify the game
     *
     * @return FormTypeInterface|View
     */
    public function putAction(Request $request, $slug)
    {
        try
        {
            // if data doesn't exist, we create it
            if(!$game = $this->container->get('ja_game.game.handler')->get($slug))
            {
                $code = Codes::HTTP_CREATED;
                $game = $this->container->get('ja_game.game.handler')->post(
                    $request->request->all()
                );
            }
            else
            {
                $code = Codes::HTTP_NO_CONTENT;
                $game = $this->container->get('ja_game.game.handler')->put(
                    $game,
                    $request->request->all()
                );
            }

            $routeOptions = array(
                'slug' => $game->getSlug()
            );

            $view = $this->routeRedirectView('api_1_get_game', $routeOptions, $code);
            if($code === Codes::HTTP_CREATED)
                $view->setData($game); // we send the data to avoid multiple requests

            return $view;
        }
        catch(InvalidFormException $exception)
        {
            return $exception->getForm();
        }
    }

    /**
     * Edit partially a game from submitted data. @todo: doc !
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Edit partially a new Game from data sent",
     *   output = "",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     400 = "The data sent is not valid",
     *     404 = "The game was not found",
     *     422 = "The game data sent contains errors"
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
     * @param string $slug The slug to identify the game
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException
     */
    public function patchAction(Request $request, $slug)
    {
        try
        {
            // if data doesn't exist, we create it
            if($game = $this->container->get('ja_game.game.handler')->get($slug))
            {
                $game = $this->container->get('ja_game.game.handler')->patch(
                    $game,
                    $request->request->all()
                );
            }
            else
                $this->createNotFoundException('The resource ' . $slug . ' was not found.');

            $routeOptions = array(
                'slug' => $game->getSlug()
            );

            $view = $this->routeRedirectView('api_1_get_game', $routeOptions, Codes::HTTP_NO_CONTENT);

            return $view;
        }
        catch(InvalidFormException $exception)
        {
            return $exception->getForm();
        }
    }

    /**
     * Delete a  partially a game from submitted data. @todo: doc !
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Edit partially a new Game from data sent",
     *   output = "",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "The data sent is not valid"
     *   }
     * )
     *
     * @todo: See for the redirection after success
     * @ Rest\View(
     *      template="JAGameBundle:Game:new.html.twig",
     * )
     *
     * @param string $slug The slug to identify the game
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException
     */
    public function deleteAction($slug)
    {
        if($game = $this->container->get('ja_game.game.handler')->get($slug))
        {
            $this->container->get('ja_game.game.handler')->delete(
                $game
            );
        }
        else
            $this->createNotFoundException('The resource ' . $slug . ' was not found.');

        $view = $this->routeRedirectView('api_1_get_games', array(), Codes::HTTP_NO_CONTENT);

        return $view;
    }
}
