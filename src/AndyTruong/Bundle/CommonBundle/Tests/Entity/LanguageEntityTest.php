<?php

namespace AndyTruong\Bundle\CommonBundle\Tests\Entity;

use AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity;
use Doctrine\ORM\Tools\SchemaTool;

class LanguageEntityTest extends EntityTestCase
{

    /**
     * @var string
     */
    protected $class_names = ['AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity'];

    public function tearDown()
    {
        $mtd = $this->em->getMetadataFactory()->getMetadataFor($this->class_name);
        $schema_tool = new SchemaTool($this->em);
        $schema_tool->dropSchema([$mtd]);
    }

    public function testCreate()
    {
        $en = LanguageEntity::fromArray(['id' => 'en', 'name' => 'English']);

        $this->assertInstanceOf($this->class_name, $en);

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
        $saved_entity = $this->em->getRepository($this->class_name)->find('en');

        $this->assertInstanceOf($this->class_name, $saved_entity);
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
        $saved_entity = $this->em->getRepository($this->class_name)->find('en');
        $this->assertEquals('Tiếng Anh', $saved_entity->getName());
    }

}
