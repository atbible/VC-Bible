<?php

namespace AndyTruong\Bundle\BibleBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\Get;
use Doctrine\ORM\EntityManagerInterface;

class RestfulController
{

    /**
     *
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Get("/versions")
     * @View()
     */
    public function getVersionsAction()
    {
        return $this->em
                ->getRepository('AndyTruong\Bundle\BibleBundle\Entity\TranslationEntity')
                ->findAll()
        ;
    }

}
