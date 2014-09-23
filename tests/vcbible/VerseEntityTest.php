<?php

namespace AndyTruong\Bible\Testcase;

use AndyTruong\Bundle\BibleBundle\Entity\VerseEntity;
use AndyTruong\Serializer\Unserializer;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit_Framework_TestCase;

/**
 * @group vcbible
 */
class VerseEntityTest extends PHPUnit_Framework_TestCase
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
        $unserializer = new Unserializer();

        $verse = $unserializer->fromArray([
            'book'        => 1,
            'chapter'     => 1,
            'number'      => 1,
            'body'        => 'In the begining, …',
            'notes'       => 'just a test verse!',
            'translation' => [
                'name'     => 'phankhoi',
                'writing'  => 'Phan Khôi',
                'language' => ['id' => 'vi', 'name' => 'Vietnamese'],
                'notes'    => 'Most stable version in Vietnamese',
            ]], 'AndyTruong\Bundle\BibleBundle\Entity\VerseEntity'
        );

        $this->assertInstanceOf('AndyTruong\Bundle\BibleBundle\Entity\VerseEntity', $verse);

        return $verse;
    }

    /**
     * @group DEBUGGG
     */
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