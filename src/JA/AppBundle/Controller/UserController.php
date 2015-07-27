<?php

namespace JA\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use FOS\RestBundle\Controller\Annotations\Put;
use FOS\RestBundle\Controller\Annotations\Get;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

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
     * @Put("/games/{slug}/favorite")
     *
     * @param $slug
     */
    public function putFavoriteAction($slug)
    {
        $user = $this->getUser();

        if(!$user)
            return new Response("Unauthorized action", 401);

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('JAAppBundle:Game');

        $game = $repository->findOneBySlug($slug);

        if(!$game)
            return new Response("Game not found", 404);

        //if the game is already among the favorites, we dont do it again
       if($user->getFavoritesGames()->contains($game))
           return new Response("No content", 204);

        $user->addFavoriteGame($game);

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        return new Response("No content", 204);
    }

    /**
     * Unset a game from favorites
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
     * @Put("/games/{slug}/favorite-remove")
     *
     * @param $slug
     */
    public function putFavoriteRemoveAction($slug)
    {
        $user = $this->getUser();

        if(!$user)
            return new Response("Unauthorized action", 401);

        $repository = $this->getDoctrine()
            ->getManager()
            ->getRepository('JAAppBundle:Game');

        $game = $repository->findOneBySlug($slug);

        if(!$game)
            return new Response("Game not found", 404);

        //if the game is not among the favorites, we do nothing
        if(!$user->getFavoritesGames()->contains($game))
            return new Response("No content", 204);

        $user->removeFavoriteGame($game);

        $em = $this->get('doctrine.orm.entity_manager');
        $em->persist($user);
        $em->flush();

        return new Response("No content", 204);
    }

    protected function getUserHandler()
    {
        return $this->container->get('ja_app.user.handler');
    }
}
