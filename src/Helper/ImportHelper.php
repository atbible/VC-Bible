<?php

namespace AndyTruong\Bible\Helper;

use AndyTruong\Bible\Application;
use AndyTruong\Bible\Entity\QueueItem;
use AndyTruong\Bible\Entity\TranslationEntity;
use AndyTruong\Bible\Entity\VerseEntity;
use AndyTruong\Serializer\Unserializer;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use RuntimeException;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ImportHelper
{

    /** @var Application */
    private $app;

    /** @var EntityManager Entity manager */
    private $em;

    /** @var string Yahoo Query. */
    private $yqlUrl = 'http://query.yahooapis.com/v1/public/yql';

    /** @var string */
    private $resourceUrl = 'http://www.tt2012.thanhkinhvietngu.net/bible';

    /** @var string */
    private $xpathTranslations = '//*[@id="bible-navigation-versions"]/li/a';

    /** @var string */
    private $queueItemClassName = 'AndyTruong\Bible\Entity\QueueItem';

    /** @var string */
    private $languageClassName = 'AndyTruong\App\Entity\LanguageEntity';

    /** @var string */
    private $translationClassName = 'AndyTruong\Bible\Entity\TranslationEntity';

    /** @var string */
    private $verseClassName = 'AndyTruong\Bible\Entity\VerseEntity';

    public function __construct(ContainerInterface $container)
    {
        $this->app = $container->get('app');
        $this->em = $this->app->getEntityManager();
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
        $path = $this->yqlUrl . '?format=json&q=' . urlencode($sql);
        $response = json_decode(file_get_contents($path), true);
        if (empty($response['query']['results'])) {
            print_r($response);
            throw new RuntimeException('Wrong YQL response.');
        }
        return reset($response['query']['results']);
    }

    public function fetchVersions()
    {
        foreach ($this->remoteQuery($this->resourceUrl, $this->xpathTranslations) as $row) {
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
        $books = $this->app->configGet(null, 'books');
        foreach ($this->fetchVersions() as $version) {
            foreach ($books as $bookNumber => $book) {
                $bookNumber = $bookNumber + 1;
                for ($chapterNumber = 1; $chapterNumber <= $book[2]; $chapterNumber++) {
                    $queueItem = $unserialize->fromArray([
                        'description' => "{$version['name']} {$bookNumber}:{$chapterNumber}",
                        'url'         => "{$this->resourceUrl}/{$version['id']}/{$bookNumber}/{$chapterNumber}",
                        'data'        => [
                            'version' => $version,
                            'book'    => $bookNumber,
                            'chapter' => $chapterNumber
                        ]], $this->queueItemClassName
                    );
                    $this->em->persist($queueItem);
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
            ->getRepository($this->queueItemClassName)
            ->findOneBy([], ['id' => 'ASC']);
    }

    public function getTranslation($name, $writing)
    {
        $unserialize = new Unserializer();

        $translation = $this->em
            ->getRepository($this->translationClassName)
            ->findOneBy(['name' => $name, 'writing' => $writing]);

        if ($translation) {
            return $translation;
        }

        if (!$language = $this->em->getRepository($this->languageClassName)->findOneBy(['id' => 'vi'])) {
            $language = $unserialize->fromArray(['id' => 'vi', 'name' => 'Vietnamese'], $this->languageClassName);
        }

        $transEntity = new TranslationEntity();
        $transEntity->setName($name);
        $transEntity->setWriting($writing);
        $transEntity->setLanguage($language);
        return $transEntity;
    }

    public function processQueueItem(QueueItem $queueItem)
    {
        $data = $queueItem->getData();
        $translation = $this->getTranslation($data['version']['id'], $data['version']['name']);
        $book = $data['book'];
        $chapter = $data['chapter'];

        foreach ($this->remoteQuery($queueItem->getUrl(), '//*[@id="bible-verses"]/div') as $row) {
            list($number, $body) = [$row['sup'], array_pop($row)];

            if (is_array($body)) {
                $body = $body['content'];
            }

            $findConds = [
                'translation' => $translation,
                'book'        => $book,
                'chapter'     => $chapter,
                'number'      => $number
            ];

            // Find existing version, if not create new one.
            if (!$verse = $this->em->getRepository($this->verseClassName)->findOneBy($findConds)) {
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
            $this->em->remove($queueItem);
            $this->em->flush();
        }
        catch (\Exception $e) {
            print_r('[ERROR] Failed to import ' . $queueItem->getUrl());
            $queueItem->setId($queueItem->getId() + 100000);
            $this->em->flush();
        }
    }

}
