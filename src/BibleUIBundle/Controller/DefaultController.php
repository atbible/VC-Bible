<?php

namespace AndyTruong\Bundle\BibleUIBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    public function indexAction()
    {
        return $this->render('AndyTruongBibleUIBundle:Default:index.html.twig');
    }

}
