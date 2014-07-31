<?php

namespace AndyTruong\Bundle\ImportBundle;

use AndyTruong\Bundle\BibleBundle\Entity\TranslationEntity;
use AndyTruong\Bundle\BibleBundle\Entity\VerseEntity;
use AndyTruong\Bundle\ImportBundle\Entity\QueueItem;
use AndyTruong\Serializer\Unserializer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Manager
{

    /**
     * Container.
     *
     * @var ContainerInterface
     */
    private $container;

    /**
     * Entity manager
     *
     * @var EntityManager
     */
    private $em;

    /**
     * Yahoo Query.
     *
     * @var string
     */
    private $yql_url = 'http://query.yahooapis.com/v1/public/yql';

    /**
     * Constructor
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->em = $this->container->get('doctrine.orm.entity_manager');
    }

    /**
     * Query remote resource via YQL service.
     *
     * @param string $url
     * @param string $xpath
     */
    private function remoteQuery($url, $xpath)
    {
        $sql = "SELECT * FROM html WHERE url = '{$url}' AND xpath = '$xpath'";
        $path = $this->yql_url . '?format=json&q=' . urlencode($sql);
        $response = json_decode(file_get_contents($path), true);
        if (empty($response['query']['results'])) {
            print_r($response);
            throw new RuntimeException('Wrong YQL response.');
        }
        return reset($response['query']['results']);
    }

    public function fetchVersions()
    {
        $url = 'http://www.tt2012.thanhkinhvietngu.net/bible/';
        $xpath = '//*[@id="bible-navigation-versions"]/li/a';
        foreach ($this->remoteQuery($url, $xpath) as $row) {
            $versions[] = array(
                'id'   => preg_replace('`^/bible/([^/]+).+$`', '$1', $row['href']),
                'name' => $row['content'],
            );
        }
        return $versions;
    }

    public function generateQueueItems()
    {
        $unserialize = new Unserializer();
        $books = require dirname(__DIR__) . '/BibleBundle/Resources/info/books.php';
        foreach ($this->fetchVersions() as $version) {
            foreach ($books as $book_number => $book) {
                $book_number = $book_number + 1;
                for ($chapter_number = 1; $chapter_number <= $book[2]; $chapter_number++) {
                    $queue_item = $unserialize->fromArray([
                        'description' => "{$version['name']} {$book_number}:{$chapter_number}",
                        'url'         => "http://www.tt2012.thanhkinhvietngu.net/bible/{$version['id']}/{$book_number}/{$chapter_number}",
                        'data'        => [
                            'version' => $version,
                            'book'    => $book_number,
                            'chapter' => $chapter_number
                        ]], 'AndyTruong\Bundle\ImportBundle\Entity\QueueItem'
                    );

                    $this->em->persist($queue_item);
                }
            }
            $this->em->flush();
        }
    }

    /**
     * @return QueueItem
     */
    public function getQueueItem()
    {
        /* @var $repository EntityRepository */
        return $this->em
                ->getRepository('AndyTruong\Bundle\ImportBundle\Entity\QueueItem')
                ->findOneBy([], ['id' => 'ASC'])
        ;
    }

    public function getTranslation($name, $writing)
    {
        $unserialize = new Unserializer();

        $translation = $this->em
            ->getRepository('AndyTruong\Bundle\BibleBundle\Entity\TranslationEntity')
            ->findOneBy(['name' => $name, 'writing' => $writing]);

        if ($translation) {
            return $translation;
        }

        if (!$language = $this->em->getRepository('AndyTruong\Bundle\CommonBundle\Entity\LanguageEntity')->findOneBy(['id' => 'vi'])) {
            $language = ['id' => 'vi', 'name' => 'Vietnamese'];
        }

        $translation = new TranslationEntity();
        $translation->setName($name);
        $translation->setWriting($writing);
        $translation->setLanguage($language);
        return $translation;
    }

    public function processQueueItem(QueueItem $queue_item)
    {
        $data = $queue_item->getData();
        $translation = $this->getTranslation($data['version']['id'], $data['version']['name']);
        $book = $data['book'];
        $chapter = $data['chapter'];

        foreach ($this->remoteQuery($queue_item->getUrl(), '//*[@id="bible-verses"]/div') as $row) {
            list($number, $body) = [$row['sup'], array_pop($row)];

            if (is_array($body)) {
                $body = $body['content'];
            }

            $verse = $this->em
                ->getRepository('AndyTruong\Bundle\BibleBundle\Entity\VerseEntity')
                ->findOneBy(['translation' => $translation, 'book' => $book, 'chapter' => $chapter, 'number' => $number]);

            if (!$verse) {
                $verse = new VerseEntity();
            }

            $verse->setTranslation($translation);
            $verse->setBook($book);
            $verse->setChapter($chapter);
            $verse->setNumber($number);
            $verse->setBody($body);

            $this->em->persist($verse);
        }

        try {
            $this->em->remove($queue_item);
            $this->em->flush();
        }
        catch (\Exception $e) {
            print_r('[ERROR] Failed to import ' . $queue_item->getUrl());
            $queue_item->setId($queue_item->getId() + 100000);
            $this->em->flush();
        }
    }

}
