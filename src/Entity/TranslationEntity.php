<?php

namespace AndyTruong\Bible\Entity;

use AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * Translation
 *
 * @ORM\Table(name="translation")
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
     * @ORM\ManyToOne(targetEntity="AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity", cascade={"all"}, fetch="LAZY")
     * @var LanguageEntity
     */
    private $language;

    /**
     * @var string
     *
     * @ORM\Column(name="notes", type="text", nullable=true)
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
     * Get language.
     *
     * @return LanguageEntity
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set language.
     *
     * @param LanguageEntity $language
     * @return TranslationEntity
     */
    public function setLanguage(LanguageEntity $language)
    {
        $this->language = $language;
        return $this;
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

}
