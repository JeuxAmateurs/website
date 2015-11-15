<?php

namespace JA\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use FOS\RestBundle\Util\Codes;
use JA\AppBundle\Entity\Game;
use JA\AppBundle\Entity\GameRepository;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Delete;

class UserController extends FOSRestController implements ClassResourceInterface
{
    /**
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets all users",
     *   output = "JA\AppBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful"
     *   }
     * )
     *
     * @Rest\View(
     *      templateVar="users",
     *      serializerGroups={"Default"}
     * )
     *
     * @return array
     */
    public function cgetAction()
    {
        $users = $this->getUserHandler()->getAll();

        return $users;
    }

    /**
     * Get single User. @todo: doc !
     *
     * @ApiDoc(
     *   resource = true,
     *   description = "Gets a Game for a given id",
     *   output = "JA\AppBundle\Entity\User",
     *   statusCodes = {
     *     200 = "Returned when successful",
     *     404 = "Returned when the user is not found"
     *   }
     * )
     *
     * @Rest\View(
     *      templateVar="user",
     *      serializerGroups={"Default"}
     * )
     *
     * @param string $username   the user's username
     *
     * @return array
     *
     * @throws NotFoundHttpException when game not exist
     */
    public function getAction($username)
    {
        if(!($user = $this->getUserHandler()->get($username))) {
            throw $this->createNotFoundException('The user ' . $username . ' was not found.');
        }

        return $user;
    }

    /**
     * Set a game among favorites
     *
     * @Put("/user/favorites/{game}")
     *
     * @ApiDoc(
     *   description = "Enable to mark the game as favorite",
     *   output = "",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     401 = "Returned if no authentication",
     *     404 = "The game was not found"
     *   }
     * )
     *
     *
     * @param $game The game's slug
     *
     * @return View
     *
     * @throws NotFoundHttpException
     */
    public function putFavoriteAction($game)
    {
        if(false === $this->get('security.authorization_checker')->isGranted('favorite', 'JA\AppBundle\Entity\Game'))
            throw $this->createAccessDeniedException();

        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository('JAAppBundle:Game');
        /** @var Game $game */
        $game = $repository->findOneBySlug($game);

        if(!$game)
            throw $this->createNotFoundException('Game ' . $game->getSlug() . ' not found');

        //if the game is already among the favorites, we don't do it again
        if(!$user->getFavoritesGames()->contains($game)) {
            $user->addFavoriteGame($game);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
        }

        return $this->routeRedirectView('api_1_get_game', array('slug' => $game->getSlug()), Codes::HTTP_NO_CONTENT);
    }

    /**
     * Unset a game from favorites
     *
     * @Delete("/user/favorites/{game}")
     *
     * @ApiDoc(
     *   description = "Enable to remove the game from favorites",
     *   output = "",
     *   statusCodes = {
     *     204 = "Returned when successful",
     *     401 = "Returned if no authentication",
     *     404 = "The game was not found"
     *   }
     * )
     *
     * @param $game The game's slug
     *
     * @return View
     */
    public function deleteFavoriteAction($game)
    {
        if(false === $this->get('security.authorization_checker')->isGranted('favorite', 'JA\AppBundle\Entity\Game'))
            throw $this->createAccessDeniedException();

        $user = $this->getUser();

        $repository = $this->getDoctrine()->getRepository('JAAppBundle:Game');
        /** @var Game $game */
        $game = $repository->findOneBySlug($game);

        //if the game is not among the favorites, we do nothing
        if ($user->getFavoritesGames()->contains($game)) {
            $user->removeFavoriteGame($game);

            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
        }

        return $this->routeRedirectView('api_1_get_game', array('slug' => $game->getSlug()), Codes::HTTP_NO_CONTENT);
    }

    protected function getUserHandler()
    {
        return $this->container->get('ja_app.user.handler');
    }
}
