<?php

namespace AndyTruong\Bible\Controller;

use AndyTruong\Bible\Application;
use AndyTruong\Bible\Entity\TranslationEntity;
use AndyTruong\Bible\Entity\VerseEntity;
use AndyTruong\Serializer\Serializer;
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
     * @url GET /
     * @view bible/index
     */
    public function getHome()
    {
        return [];
    }

    /**
     * @url GET /admin
     * @view bible/index
     */
    protected function getAdminHome()
    {
        return ['isAdmin' => true];
    }

    /**
     * @url GET /bible/versions
     */
    public function getVersionsAction()
    {
        return array_map(function(TranslationEntity $translation) {
            return [
                'id'       => $translation->getId(),
                'name'     => $translation->getName(),
                'writing'  => $translation->getWriting(),
                'language' => [
                    'id'        => $translation->getLanguage()->getId(),
                    'direction' => $translation->getLanguage()->getDirection(),
                ],
            ];
        }, $this->em
                ->getRepository('AndyTruong\Bible\Entity\TranslationEntity')
                ->findAll())
        ;
    }

    /**
     * @url GET /bible/books
     */
    public function getBooksAction()
    {
        return $this->app->configGet(null, 'books');
    }

    /**
     * @url GET /bible/{translation}/{book}/{chapterNumber}
     * @param string $translation
     * @param string $book
     * @param int $chapterNumber
     */
    public function getChapterAction($translation, $book, $chapterNumber)
    {
        if (is_numeric($book)) {
            $bookNumber = $book;
        }
        else {
            foreach ($this->getBooksAction() as $i => $bookInfo) {
                if ($book === $bookInfo[0]) {
                    $bookNumber = $i + 1;
                }
            }
        }

        if (!isset($bookNumber)) {
            return [];
        }

        $query = $this->em->createQuery(
            "SELECT v.id, v.book, v.chapter, v.number, v.body"
            . " FROM AndyTruong\Bible\Entity\VerseEntity v"
            . "     JOIN v.translation t"
            . " WHERE t.name = :translation AND v.book = :book AND v.chapter = :chapter"
            . " ORDER BY v.number"
        );

        $query->setParameter(':translation', $translation);
        $query->setParameter(':book', $bookNumber);
        $query->setParameter(':chapter', $chapterNumber);

        return $query->getResult();
    }

    /**
     * Full text search.
     *
     * @url GET /bible/find/{translation}/{keywords}
     * @param string $translation
     * @param string $keywords
     */
    public function findAction($translation, $keywords)
    {
        /* @var $query Query  */
        $query = $this->em->createQuery(
            "SELECT v.book, v.chapter, v.number, v.body"
            . " FROM AndyTruong\Bible\Entity\VerseEntity v"
            . "     JOIN v.translation t"
            . " WHERE t.name = :translation AND v.body LIKE :keywords"
            . " ORDER BY v.book, v.chapter, v.number"
        );
        $query->setParameter(':translation', $translation);
        $query->setParameter(':keywords', "%{$keywords}%");

        return $query->execute();
    }

    /**
     * Update a verse
     *
     * @url PUT /bible/verse/{id}
     */
    public function updateVerse($id, array $input = [])
    {
        /* @var $verse VerseEntity */
        if (!$verse = $this->em->getRepository('AndyTruong\Bible\Entity\VerseEntity')->find($id)) {
            return ['status' => 'FAILED', 'message' => 'Verse not found'];
        }

        if (!isset($input['writing'])) {
            return ['status' => 'FAILED', 'message' => 'Invalid input structure. `writing` property must be sent.'];
        }

        $verse->setBody($input['writing']);
        $this->em->persist($verse);
        $this->em->flush();
        return ['status' => 'OK', 'verse' => (new Serializer)->toArray($verse)];
    }

}
