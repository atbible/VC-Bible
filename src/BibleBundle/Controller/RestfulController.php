<?php

namespace AndyTruong\Bundle\BibleBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations\Get;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;

/**
 * @Cache(expires="+ 1 month", public=true)
 */
class RestfulController
{

    /**
     * Entity manager.
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * Constructor.
     *
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Get("/versions")
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
    public function getChapterAction($translation, $book, $chapter_number)
    {
        if (is_numeric($book)) {
            $book_number = $book;
        }
        else {
            foreach ($this->getBooksAction() as $i => $book_info) {
                if ($book === $book_info[0]) {
                    $book_number = $i + 1;
                }
            }
        }

        if (!isset($book_number)) {
            return [];
        }

        $query = $this->em->createQuery(
            "SELECT v.book, v.chapter, v.number, v.body"
            . " FROM AndyTruong\Bundle\BibleBundle\Entity\VerseEntity v"
            . "     JOIN v.translation t"
            . " WHERE t.name = :translation AND v.book = :book AND v.chapter = :chapter"
            . " ORDER BY v.number"
        );

        $query->setParameter(':translation', $translation);
        $query->setParameter(':book', $book_number);
        $query->setParameter(':chapter', $chapter_number);

        return $query->getResult();
    }

    /**
     * Full text search.
     *
     * @Get("/find/{translation}/{keywords}")
     * @param string $translation
     * @param string $keywords
     */
    public function findAction($translation, $keywords)
    {
        /* @var $query \Doctrine\ORM\Query  */
        $query = $this->em->createQuery(
            "SELECT v.book, v.chapter, v.number, v.body"
            . " FROM AndyTruong\Bundle\BibleBundle\Entity\VerseEntity v"
            . "     JOIN v.translation t"
            . " WHERE t.name = :translation AND v.body LIKE :keywords"
            . " ORDER BY v.book, v.chapter, v.number"
        );
        $query->setParameter(':translation', $translation);
        $query->setParameter(':keywords', "%{$keywords}%");

        return $query->execute();
    }

}
