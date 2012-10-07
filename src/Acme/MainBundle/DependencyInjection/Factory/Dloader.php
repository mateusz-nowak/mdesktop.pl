<?php

namespace Acme\MainBundle\DependencyInjection\Factory;

use ArrayIterator, Exception;
use Acme\MainBundle\DependencyInjection\FactoryInterface;
use Acme\MainBundle\DependencyInjection\WebProxyClient;
use Symfony\Component\DomCrawler\Crawler;
use Acme\MainBundle\Entity\Track;
use Doctrine\ORM\EntityManager;
use Buzz\Browser;

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

    public function __construct(WebProxyClient $client, EntityManager $manager)
    {
        $this->webProxyClient = $client;
        $this->entityManager = $manager;
    }

    public function getTrackInfo($trackRemoteKey)
    {

    }

    public function	searchForTrack($query, $page)
    {
        $response = (string) $this->getResponse(sprintf(self::$searchTrackUrl, $page, urlencode($query)));
        
        $trackArray = array();
        $crawler = new Crawler;
        $crawler->addHtmlContent($response);

        foreach($crawler->filter('.page ul li a') as $item)
        {
            preg_match('/\/plik\/(.*?)\,(?P<remote_id>\d+)/', $item->getAttribute('href'), $tmp);
            
            $track = new Track;
            $track->setTitle(ucwords($item->nodeValue));
            $track->setRemoteId((int)$tmp['remote_id']);
            
            $trackArray[] = $track;
        }
        
        foreach($trackArray as $trackEntity) 
        {
            $this->entityManager->persist($trackEntity);
            $this->entityManager->flush();
        }
        
        return new ArrayIterator($trackArray);
    }
    
    public function processDownload(Track $track)
    {
        $trackDownloadUrl = sprintf(self::$trackDownloadUrl, $track->getRemoteId());
        
        return $trackDownloadUrl;
    }

    protected function getResponse($url)
    {
        $this->webProxyClient->setParameters(array(
            'url' => $url,
        ));

        return $this->webProxyClient->process();
    }
}
