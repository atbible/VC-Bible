<?php

namespace AndyTruong\Bundle\CommonBundle\Tests\Entity;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EntityTestCase extends KernelTestCase
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string[]
     */
    protected $class_names = [];

    public function setUp()
    {
        self::bootKernel();

        $this->em = static::$kernel->getContainer()
            ->get('doctrine')
            ->getManager()
        ;

        if (!empty($this->class_names)) {
            $schema_tool = new SchemaTool($this->em);

            foreach ($this->class_names as $class_name) {
                $mtd = $this->em->getMetadataFactory()->getMetadataFor($class_name);
                $schema_tool->dropSchema([$mtd]);
                $schema_tool->createSchema([$mtd]);
            }
        }
    }

}
