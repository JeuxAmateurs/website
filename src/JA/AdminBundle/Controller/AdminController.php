<?php

namespace JA\AdminBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Admin controller.
 *
 */
class AdminController extends Controller
{
    /**
     * Displays all Game entities in a page.
     *
     */
    public function indexAction()
    {
        return $this->render('JAAdminBundle:Admin:index.html.twig');
    }
	
}
