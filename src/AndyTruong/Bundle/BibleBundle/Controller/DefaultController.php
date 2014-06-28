<?php

namespace AndyTruong\Bundle\BibleBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('AndyTruongBibleBundle:Default:index.html.twig', array('name' => $name));
    }
}
