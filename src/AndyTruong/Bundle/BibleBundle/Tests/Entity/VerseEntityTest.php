<?php

namespace AndyTruong\Bundle\BibleBundle\Tests\Entity;

use AndyTruong\Bundle\BibleBundle\Entity\TranslationEntity;
use AndyTruong\Bundle\BibleBundle\Entity\VerseEntity;
use AndyTruong\Bundle\CommonBundle\Tests\Entity\EntityTestCase;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @group vcbible
 */
class VerseEntityTest extends EntityTestCase
{

    /**
     * @var EntityManagerInterface
     */
    protected $em;

    /**
     * @var string
     */
    protected $class_names = [
        'AndyTruong\Bundle\BibleBundle\Entity\VerseEntity',
        'AndyTruong\Bundle\BibleBundle\Entity\TranslationEntity',
        'AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity'
    ];

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
                'translation' => TranslationEntity::fromArray([
                    'name' => 'phankhoi',
                    'writing' => 'Phan Khôi',
                    'language' => \AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity::fromArray([
                        'id' => 'vi',
                        'name' => 'Vietnamese'
                    ]),
                    'notes' => 'Most stable version in Vietnamese',
                ])
        ]);

        $this->assertInstanceOf('AndyTruong\Bundle\BibleBundle\Entity\VerseEntity', $verse);

        return $verse;
    }

    public function testCreate()
    {
        $stub = $this->getStub();
        $this->em->persist($stub->getTranslation()->getLanguage());
        $this->em->persist($stub->getTranslation());
        $this->em->persist($stub);
        $this->em->flush();
        return $stub;
    }

    /**
     * @return VerseEntity
     */
    public function testGet()
    {
        $stub = $this->testCreate();

        $verse = $this->em
            ->getRepository('AndyTruong\Bundle\BibleBundle\Entity\VerseEntity')
            ->find($stub->getId())
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
            ->find($verse->getId())
        ;

        $this->assertEquals('just a test verse! [updated]', $updated_verse->getNotes());
    }

}
