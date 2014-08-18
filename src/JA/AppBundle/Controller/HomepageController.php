<?php

namespace JA\AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\ApiDoc;

class HomepageController extends FOSRestController
{
    /**
     * @ApiDoc(
     *      resource = false,
     *      description = "Entry point"
     * )
     *
     * @Rest\Get(path="/")
     *
     * @Rest\View(
     *      template="JAAppBundle:Homepage:index.html.twig"
     * )
     */
    public function indexAction()
    {
        return "Homepage";
    }
}
