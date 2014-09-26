<?php

namespace AndyTruong\Bible\Testcase;

use AndyTruong\Bible\Application;
use AndyTruong\Bible\Entity\VerseEntity;
use AndyTruong\Serializer\Unserializer;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit_Framework_TestCase;

class VerseEntityTest extends PHPUnit_Framework_TestCase
{

    /** @var Application */
    private $app;

    /** @var EntityManagerInterface */
    private $em;

    /**
     * @var string
     */
    protected $class_names = [
        'AndyTruong\Bible\Entity\VerseEntity',
        'AndyTruong\Bible\Entity\TranslationEntity',
        '\AndyTruong\App\Entity\LanguageEntity'
    ];

    protected function setUp()
    {
        parent::setUp();
        $this->app = new Application(dirname(__DIR__) . '/fixtures');
        $this->em = $this->app->getEntityManager();
    }

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
            ]], 'AndyTruong\Bible\Entity\VerseEntity'
        );

        $this->assertInstanceOf('AndyTruong\Bible\Entity\VerseEntity', $verse);

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
            ->getRepository('AndyTruong\Bible\Entity\VerseEntity')
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
            ->getRepository('AndyTruong\Bible\Entity\VerseEntity')
            ->find($verse->getId())
        ;

        $this->assertEquals('just a test verse! [updated]', $updated_verse->getNotes());
    }

}
