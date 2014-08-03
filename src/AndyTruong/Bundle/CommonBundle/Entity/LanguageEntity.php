<?php

namespace AndyTruong\Bundle\CommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * LanguageEntity
 */
class LanguageEntity
{

    /**
     * Language written left to right. Possible value of $language->direction.
     */
    const DIRECTION_LTR = 0;

    /**
     * Language written right to left. Possible value of $language->direction.
     */
    const DIRECTION_RTL = 1;

    /**
     * @var integer
     *
     * @ORM\Id
     * @ORM\Column(name="id", type="string", length=8, unique=true, nullable=false)
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var integer
     */
    private $weight = 0;

    /**
     * @var integer
     */
    private $direction = self::DIRECTION_LTR;

    /**
     * Get id
     *
     * @return string
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set id
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return LanguageEntity
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
     * Set weight
     *
     * @param integer $weight
     * @return LanguageEntity
     */
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }

    /**
     * Get weight
     *
     * @return integer
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set direction
     *
     * @param integer $direction
     * @return LanguageEntity
     */
    public function setDirection($direction)
    {
        $this->direction = $direction;

        return $this;
    }

    /**
     * Get direction
     *
     * @return integer
     */
    public function getDirection()
    {
        return $this->direction;
    }

}
