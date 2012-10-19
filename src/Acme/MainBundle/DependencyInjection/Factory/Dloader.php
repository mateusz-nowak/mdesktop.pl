<?php

namespace Acme\MainBundle\DependencyInjection\Factory;

use ArrayIterator, Exception, stdClass, RuntimeException;
use Doctrine\ORM\EntityManager;
use Acme\MainBundle\Entity\Track;
use Symfony\Component\DomCrawler\Crawler;
use Acme\MainBundle\DependencyInjection\Downloader;
use Acme\MainBundle\DependencyInjection\WebProxyClient;
use Acme\MainBundle\DependencyInjection\FactoryInterface;

class Dloader implements FactoryInterface
{
    /** @var $string $searchTrackUrl */
    protected static $searchTrackUrl = 'http://dloader.pl/szukaj/%s/%s';

    /** @var $string $trackInfoUrl */
    protected static $trackInfoUrl = 'http://dloader.pl/plik/dev,%s.html';

    /** @var $string $trackDownloadUrl */
    protected static $trackDownloadUrl = 'http://dloader.pl/pobierz/dev,%s.html';

    /** @var $webProxyClient \Acme\MainBundle\Model\WebProxyClient */
    protected $webProxyClient;

    /** @var $webProxyClient \Acme\MainBundle\Model\WebProxyClient */
    protected $entityManager;

    /** @var $downloaderContainer \Acme\MainBundle\DependencyInjection\Downloader */
    protected $downloaderContainer;

    /** @var $prefixTitle string */
    protected $prefixTitle;

    public function __construct(WebProxyClient $client, EntityManager $manager, Downloader $downloader)
    {
        $this->webProxyClient = $client;
        $this->entityManager = $manager;
        $this->downloaderContainer = $downloader;
    }

    public function getTrackInfo($trackRemoteKey)
    {
        $crawler = new Crawler;
        $crawler->addHtmlContent($response = (string) $this->getResponse(sprintf(self::$trackInfoUrl, $trackRemoteKey)));

        if (preg_match('/Wystąpił błąd/', $response)) {
            return;
        }

        preg_match('/Plik ten ma rozmiar (?P<size>.+? MB)/', $crawler->filter('div.link')->eq(2)->text(), $tmpMeta);

        if (!isset($tmpMeta['size'])) {
            throw new RuntimeException('Can not open this entity');
        }

        $trackMetaData = new stdClass;
        $trackMetaData->remote = $trackRemoteKey;
        $trackMetaData->title = $crawler->filter('h2')->eq(2)->text();
        $trackMetaData->size = $tmpMeta['size'];

        return $trackMetaData;
    }

    public function	searchForTrack($query, $page, &$isNextPage)
    {
        $query = iconv(mb_detect_encoding($query), 'ASCII//TRANSLIT', $query);
        $response = (string) $this->getResponse(sprintf(self::$searchTrackUrl, $page, $query));

        $trackArray = array();
        $crawler = new Crawler;
        $crawler->addHtmlContent($response);

        foreach ($crawler->filter('.page ul li a') as $item) {
            preg_match('/\/plik\/(.*?)\,(?P<remote_id>\d+)/', $item->getAttribute('href'), $tmp);

            $trackArray[] = array(
                'title' => str_replace('/', '', ucwords($item->nodeValue)),
                'remote' => $tmp['remote_id'],
            );
        }

        if (preg_match('/Następna/', $response)) {
            $isNextPage = true;
        }

        return new ArrayIterator($this->entityManager->getRepository('AcmeMainBundle:Track')->batchInsertTracks($trackArray));
    }

    public function processDownload(Track $track)
    {
        return $this->downloaderContainer->process($track, sprintf(self::$trackDownloadUrl, $track->getRemote()));
    }

    protected function getResponse($url)
    {
        $this->webProxyClient->setParameters(array(
            'url' => $url,
        ));

        return $this->webProxyClient->process();
    }
}
