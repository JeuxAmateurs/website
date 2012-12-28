<?php

namespace JA\SiteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('JASiteBundle:Default:index.html.twig');
    }
}
