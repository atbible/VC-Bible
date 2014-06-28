<?php

namespace AndyTruong\Bundle\BibleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Verse
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="AndyTruong\Bundle\BibleBundle\Entity\VerseRepository")
 */
class VerseEntity
{

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     * @ORM\Id
     */
    private $number;

    /**
     * @var integer
     *
     * @ORM\Column(name="book", type="integer")
     * @ORM\Id
     */
    private $book;

    /**
     * @var integer
     *
     * @ORM\Column(name="chapter", type="integer")
     * @ORM\Id
     */
    private $chapter;

    /**
     * @var string
     *
     * @ORM\Column(name="body", type="text")
     */
    private $body;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text")
     */
    private $notes;

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
     * Get notes
     *
     * @return string
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Create new verse from associative array.
     *
     * @param array $input
     */
    public static function fromArray(array $input)
    {
        $me = new static();

        foreach ($input as $k => $v) {
            switch ($k) {
                case 'number':
                    $me->setNumber($v);
                    break;
                case 'book':
                    $me->setBook($v);
                    break;
                case 'chapter':
                    $me->setChapter($v);
                    break;
                case 'body':
                    $me->setBody($v);
                    break;
                case 'notes':
                    $me->setNotes($v);
                    break;
                default:
                    throw new \InvalidArgumentException(\sprintf('Key %s is not supported.', $k));
            }
        }

        return $me;
    }

}
