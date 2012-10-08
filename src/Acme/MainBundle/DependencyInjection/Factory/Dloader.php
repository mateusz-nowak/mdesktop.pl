<?php

namespace Acme\MainBundle\DependencyInjection\Factory;

use ArrayIterator, Exception;
use Doctrine\ORM\EntityManager;
use Acme\MainBundle\ObjectValue\Track;
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

    }

    public function	searchForTrack($query, $page, &$isNextPage)
    {
        $response = (string) $this->getResponse(sprintf(self::$searchTrackUrl, $page, urlencode($query)));

        $trackArray = array();
        $crawler = new Crawler;
        $crawler->addHtmlContent($response);

        foreach ($crawler->filter('.page ul li a') as $item) 
        {
            preg_match('/\/plik\/(.*?)\,(?P<remote_id>\d+)/', $item->getAttribute('href'), $tmp);

            $trackArray[] = array(
                'title' => ucwords($item->nodeValue),
                'remote' => $tmp['remote_id'],
            );
        }
        
        if(preg_match('/Następna/', $response))
        {
            $isNextPage = true;
        }

        return new ArrayIterator($trackArray);
    }

    public function processDownload(Track $track)
    {
        return $this->downloaderContainer->process($track, sprintf(self::$trackDownloadUrl, $track->getRemoteId()));
    }

    protected function getResponse($url)
    {
        $this->webProxyClient->setParameters(array(
            'url' => $url,
        ));

        return $this->webProxyClient->process();
    }
}
