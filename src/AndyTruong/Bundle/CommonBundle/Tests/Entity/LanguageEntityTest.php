<?php

namespace AndyTruong\Bundle\CommonBundle\Tests\Entity;

use AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity;
use AndyTruong\Serializer\Unserializer;

class LanguageEntityTest extends EntityTestCase
{

    /**
     * @var string
     */
    protected $class_names = ['AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity'];

    public function testCreate()
    {
        $unserializer = new Unserializer();
        $en = $unserializer->fromArray(
            ['id' => 'en', 'name' => 'English'], 'AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity'
        );
        $this->assertInstanceOf('AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity', $en);
        $this->em->persist($en);
        $this->em->flush();
    }

    /**
     * @return LanguageEntity
     */
    public function testGet()
    {
        $this->testCreate();

        /* @var $saved_entity LanguageEntity */
        $saved_entity = $this
            ->em
            ->getRepository('AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity')
            ->find('en');

        $this->assertInstanceOf('AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity', $saved_entity);
        $this->assertEquals(['en', 'English', 0, LanguageEntity::DIRECTION_LTR], [
            $saved_entity->getId(),
            $saved_entity->getName(),
            $saved_entity->getWeight(),
            $saved_entity->getDirection()
        ]);

        return $saved_entity;
    }

    public function testUpdate()
    {
        $language = $this->testGet();
        $language->setName('Tiếng Anh');
        $this->em->persist($language);
        $this->em->flush();

        /* @var $saved_entity LanguageEntity */
        $saved_entity = $this
            ->em
            ->getRepository('AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity')
            ->find('en');

        $this->assertEquals('Tiếng Anh', $saved_entity->getName());
    }

}
