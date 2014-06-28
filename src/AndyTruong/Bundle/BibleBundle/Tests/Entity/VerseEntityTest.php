<?php

namespace AndyTruong\Bundle\BibleBundle\Tests\Entity;

use AndyTruong\Bundle\BibleBundle\Entity\VerseEntity;
use AndyTruong\Bundle\CommonBundle\Tests\Entity\EntityTestCase;
use Doctrine\ORM\EntityManagerInterface;

class VerseEntityTest extends EntityTestCase
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $class_names = ['AndyTruong\Bundle\BibleBundle\Entity\VerseEntity'];

    /**
     * @return VerseEntity
     */
    private function getStub()
    {
        $verse = VerseEntity::fromArray([
                'book' => 1,
                'chapter' => 1,
                'number' => 1,
                'body' => 'In the begining, …',
                'notes' => 'just a test verse!',
        ]);

        $this->assertInstanceOf('AndyTruong\Bundle\BibleBundle\Entity\VerseEntity', $verse);

        return $verse;
    }

    public function testCreate()
    {
        $stub = $this->getStub();

        $this->em->persist($stub);
        $this->em->flush();
    }

    /**
     * @return VerseEntity
     */
    public function testGet()
    {
        $this->testCreate();

        $verse = $this->em
            ->getRepository('AndyTruong\Bundle\BibleBundle\Entity\VerseEntity')
            ->find(['book' => 1, 'chapter' => 1, 'number' => 1])
        ;

        $this->assertEquals('just a test verse!', $verse->getNotes());

        return $verse;
    }

    public function testUpdate()
    {
        $verse = $this->testGet();
        $verse->setNotes($verse->getNotes() . ' [updated]');

        $updated_verse = $this->em
            ->getRepository('AndyTruong\Bundle\BibleBundle\Entity\VerseEntity')
            ->find(['book' => 1, 'chapter' => 1, 'number' => 1])
        ;

        $this->assertEquals('just a test verse! [updated]', $updated_verse->getNotes());
    }

}
