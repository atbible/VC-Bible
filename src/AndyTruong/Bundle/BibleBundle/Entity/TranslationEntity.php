<?php

namespace AndyTruong\Bundle\BibleBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use InvalidArgumentException;

/**
 * Translation
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class TranslationEntity
{

    /**
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="writing", type="string", length=255)
     */
    private $writing;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text")
     */
    private $notes;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return TranslationEntity
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set writing
     *
     * @param string $writing
     * @return TranslationEntity
     */
    public function setWriting($writing)
    {
        $this->writing = $writing;

        return $this;
    }

    /**
     * Get writing
     *
     * @return string
     */
    public function getWriting()
    {
        return $this->writing;
    }

    /**
     * Set notes
     *
     * @param string $notes
     * @return TranslationEntity
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
     * Create new translation object from array.
     *
     * @param array $input
     * @return TranslationEntity
     */
    public static function fromArray(array $input)
    {
        $me = new static();

        foreach ($input as $k => $v) {
            switch ($k) {
                case 'name':
                    $me->setName($v);
                    break;
                case 'writing':
                    $me->setWriting($v);
                    break;
                case 'notes':
                    $me->setNotes($v);
                    break;
                default:
                    throw new InvalidArgumentException(sprintf('Key %s is not supported.', $k));
            }
        }

        return $me;
    }

}
