<?php

namespace AndyTruong\Bible\Entity;

use AndyTruong\App\Entity\LanguageEntity;
use AndyTruong\Bible\Entity\TranslationEntity;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

/**
 * Translation
 *
 * @Entity
 * @Table(name="translation")
 */
class TranslationEntity
{

    /**
     * @Column(name="id", type="integer")
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @var int
     */
    private $id;

    /**
     * @var string
     *
     * @Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @Column(name="writing", type="string", length=255)
     */
    private $writing;

    /**
     * @ManyToOne(targetEntity="AndyTruong\App\Entity\LanguageEntity", cascade={"all"}, fetch="LAZY")
     * @var LanguageEntity
     */
    private $language;

    /**
     * @var string
     *
     * @Column(name="notes", type="text", nullable=true)
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
