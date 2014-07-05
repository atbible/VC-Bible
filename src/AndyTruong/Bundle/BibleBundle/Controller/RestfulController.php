<?php

namespace AndyTruong\Bundle\BibleBundle\Controller;

use FOS\RestBundle\Controller\Annotations\View;
use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use FOS\RestBundle\Controller\Annotations\Get;
use AndyTruong\Bundle\BibleBundle\Entity\TranslationEntity;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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

    /**
     * @Get("/books")
     * @return array
     */
    public function getBooksAction()
    {
        return (require dirname(__DIR__) . '/Resources/info/books.php');
    }

    /**
     * @Get("/{translation}/{book}/{chapter_number}")
     * @param string $translation
     * @param string $book
     * @param int $chapter_number
     */
    public function getChapterAction($translation, $book, $chapter_number) {
        foreach ($this->getBooksAction() as $i => $book_info) {
            if ($book === $book_info[0]) {
                $book_number = $i + 1;
            }
        }

        if (!isset($book_number)) {
            return [];
        }

        $query = $this->em
            ->createQuery(
                "SELECT v.book, v.chapter, v.number, v.body"
                . " FROM AndyTruong\Bundle\BibleBundle\Entity\VerseEntity v"
                . "     JOIN v.translation t"
                . " WHERE t.name = ?1 AND v.book = ?2 AND v.chapter = ?3"
            )
        ;

        $query->setParameter(1, $translation);
        $query->setParameter(2, $book_number);
        $query->setParameter(3, $chapter_number);

        return $query->getResult();
    }

}
