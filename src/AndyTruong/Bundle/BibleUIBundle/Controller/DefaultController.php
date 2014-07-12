<?php

namespace AndyTruong\Bundle\BibleUIBundle\Controller;

use AndyTruong\Bundle\BibleBundle\Entity\TranslationEntity;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{

    /**
     * @Route("/ui/{translation}/{book}/{chapter}")
     * @Template()
     */
    public function indexAction(TranslationEntity $translation, $book = 1, $chapter = 1)
    {
        return array('translation' => $translation, 'book' => $book, 'chapter' => $chapter);
    }

}
