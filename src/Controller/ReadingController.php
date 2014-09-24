<?php

namespace AndyTruong\Bible\Controller;

use AndyTruong\Bible\Application;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;

class ReadingController
{

    /** @var Application */
    private $app;

    /** @var EntityManagerInterface Entity manager. */
    private $em;

    public function __construct(Application $app)
    {
        $this->app = $app;
        $this->em = $app->getEntityManager();
    }

    /**
     * @url GET /versions
     */
    public function getVersionsAction()
    {
        return $this->em
                ->getRepository('AndyTruong\Bundle\BibleBundle\Entity\TranslationEntity')
                ->findAll()
        ;
    }

    /**
     * @url GET /books
     */
    public function getBooksAction()
    {
        return $this->app->configGet(null, 'books');
    }

    /**
     * @url GET /{translation}/{book}/{chapter_number}
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
     * @url GET /find/{translation}/{keywords}
     * @param string $translation
     * @param string $keywords
     */
    public function findAction($translation, $keywords)
    {
        /* @var $query Query  */
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
