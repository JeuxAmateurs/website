<?php

namespace JA\GameBundle\Controller;

use JA\GameBundle\Entity\Game;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Util\Codes;
use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\View\View;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @Rest\View(
     *      templateVar="games",
     *      serializerGroups={"list"}
     * )
     *
     * @return array
     *
     */
    public function cgetAction()
    {
        $games = $this->getGameHandler()->getAll();

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
     * @Rest\View(
     *      templateVar="game",
     *      serializerGroups={"details"}
     * )
     *
     * @param string $slug   the game slug
     *
     * @return array
     *
     * @throws NotFoundHttpException when game not exist
     */
    public function getAction($slug)
    {
        if(!($game = $this->getGameHandler()->get($slug))) {
            throw $this->createNotFoundException("The resource '". $slug ."' was not found.");
        }

        return $game;
    }

    /**
     * Presents the form to use to create a new Game.
     *
     * @ApiDoc(
     *   resource = false,
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
            $newGame = $this->getGameHandler()->post(
                $request->request->get(GameType::$name)
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
     * Presents the form to use to edit a Game.
     *
     * @ApiDoc(
     *   resource = false,
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "When the Game was not found"
     *   }
     * )
     *
     * @Rest\View()
     *
     * @param string $slug The game slug to edit
     *
     * @return FormTypeInterface
     *
     * @throws NotFoundHttpException
     */
    public function editAction($slug)
    {
        if(!$game = $this->getGameHandler()->get($slug))
        {
            throw $this->createNotFoundException('The resource ' . $slug . ' was not found.');
        }

        $form = $this->createForm(
            new GameType(),
            $game,
            array(
                'action' => $this->generateUrl(
                        'api_1_put_game',
                        array('slug' => $game->getSlug())
                    ),
                'method' => 'put'
            )
        );

        return $form;
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
     *      template="JAGameBundle:Game:edit.html.twig",
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
            if(!$game = $this->getGameHandler()->get($slug))
            {
                $code = Codes::HTTP_CREATED;
                $game = $this->getGameHandler()->post(
                    $request->request->get(GameType::$name)
                );
            }
            else
            {
                $code = Codes::HTTP_NO_CONTENT;
                $game = $this->getGameHandler()->put(
                    $game,
                    $request->request->get(GameType::$name)
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
     *   description = "Edit partially a Game from data sent",
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
     *      template="",
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
            if($game = $this->getGameHandler()->get($slug))
            {
                $game = $this->getGameHandler()->patch(
                    $game,
                    $request->request->get(GameType::$name)
                );
            }
            else
                throw $this->createNotFoundException('The resource ' . $slug . ' was not found.');

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
     * Get a form to delete a game. @todo: doc !
     *
     * @ApiDoc(
     *   resource = false,
     *   description = "Get a form to delete a game",
     *   output = "",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     404 = "The data sent is not valid"
     *   }
     * )
     *
     * @Rest\View(
     *      template="JAGameBundle:Game:remove.html.twig",
     *      templateVar="form"
     * )
     *
     * @param string $slug The slug to identify the game
     *
     * @return FormTypeInterface|View
     *
     * @throws NotFoundHttpException
     */
    public function removeAction($slug)
    {
        $deleteForm = $this->createDeleteForm($slug);

        return $deleteForm;
    }

    private function createDeleteForm($slug)
    {
        return $this->createFormBuilder()
            ->add('Delete', 'submit')
            ->setAction($this->generateUrl('api_1_delete_game', array('slug' => $slug)))
            ->setMethod('delete')
            ->getForm()
            ;
    }

    /**
     * Delete a game. @todo: doc !
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Delete a Game",
     *   output = "",
     *   statusCodes = {
     *     204 = "Returned when successful"
     *   }
     * )
     *
     * @todo: See for the redirection after success
     * @Rest\View(
     *      template="JAGameBundle:Game:remove.html.twig",
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
        if($game = $this->getGameHandler()->get($slug))
        {
            $this->getGameHandler()->delete($game);
        }

        $view = $this->routeRedirectView('api_1_get_games', array(), Codes::HTTP_NO_CONTENT);

        return $view;
    }

    private function getGameHandler()
    {
        return $this->container->get('ja_game.game.handler');
    }
}
