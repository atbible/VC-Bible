<?php

namespace AndyTruong\Bible\Entity;

use AndyTruong\Bible\Entity\TranslationEntity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Verse
 *
 * @Table(name="verse")
 * @Entity()
 */
class VerseEntity
{

    /**
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @var integer
     * @Column(name="number", type="integer")
     */
    private $number;

    /**
     * @ManyToOne(targetEntity="TranslationEntity", cascade={"all"}, fetch="LAZY")
     * @var TranslationEntity
     */
    private $translation;

    /**
     * @var integer
     * @Column(name="book", type="integer")
     */
    private $book;

    /**
     * @var integer
     * @Column(name="chapter", type="integer")
     */
    private $chapter;

    /**
     * @var string
     * @Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     * @Column(name="notes", type="text", nullable=true)
     */
    private $notes;

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Set id
     *
     * @param int $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return integer
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * Set book
     *
     * @param integer $book
     * @return VerseEntity
     */
    public function setBook($book)
    {
        $this->book = $book;
        return $this;
    }

    /**
     * Get book
     *
     * @return integer
     */
    public function getBook()
    {
        return $this->book;
    }

    /**
     * Set chapter
     *
     * @param integer $chapter
     * @return VerseEntity
     */
    public function setChapter($chapter)
    {
        $this->chapter = $chapter;
        return $this;
    }

    /**
     * Get chapter
     *
     * @return integer
     */
    public function getChapter()
    {
        return $this->chapter;
    }

    /**
     * Set body
     *
     * @param string $body
     * @return VerseEntity
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    /**
     * Get body
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return VerseEntity
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
        return $this;
    }

    /**
     * Get translation.
     *
     * @return TranslationEntity
     */
    public function getTranslation()
    {
        return $this->translation;
    }

    /**
     * Set translation.
     *
     * @param TranslationEntity $translation
     * @return VerseEntity
     */
    public function setTranslation(TranslationEntity $translation)
    {
        $this->translation = $translation;
        return $this;
    }

    /**
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

}
