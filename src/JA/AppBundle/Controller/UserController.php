<?php

namespace JA\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Routing\ClassResourceInterface;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

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

    protected function getUserHandler()
    {
        return $this->container->get('ja_app.user.handler');
    }
}
