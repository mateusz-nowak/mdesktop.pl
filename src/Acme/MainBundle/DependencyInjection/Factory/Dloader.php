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
    protected static $searchTrackUrl = 'http://dloader.pl/szukaj/%s';
	
	/** @var $string $searchTrackUrl */
    protected static $searchTrackUrlPage = 'http://dloader.pl/szukaj/%s/%s';

    /** @var $string $trackInfoUrl */
    protected static $trackInfoUrl = 'http://dloader.pl/plik/dev,%s.html';

    /** @var $string $trackDownloadUrl */
    protected static $trackDownloadUrl = 'http://s1.dloader.pl/download.php?link=http://%s.wrzuta.pl/audio/%s/dev';

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
        $query = urlencode(mb_strtolower(iconv(mb_detect_encoding($query), 'ASCII//TRANSLIT', $query), 'UTF-8'));
		
		try {
			if($page == 1) {
				$response = (string) $this->getResponse(sprintf(self::$searchTrackUrl, $query));
			} else {
				$response = (string) $this->getResponse(sprintf(self::$searchTrackUrlPage, $page-1, $query));
			}
		} catch (RuntimeException $e) {
			throw new RuntimeException('Nie można było wyszukać tego utworu. Wystąpił problem z połaczeniem z zewnętrzną bazą danych - spróbuj poźniej.');
		}
        
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
    	$crawler = new Crawler;
    	$response = (string) $this->getResponse(sprintf(self::$trackInfoUrl, $track->getRemote()));
		preg_match('/key=(?P<key>.*?)&login=(?P<login>.*?)&/', $response, $key);
		
    	$downloadUrl = sprintf(self::$trackDownloadUrl, $key['login'], $key['key']);
        return $this->downloaderContainer->process($track, $downloadUrl);
    }

    protected function getResponse($url)
    {
        $this->webProxyClient->setParameters(array(
            'url' => $url,
        ));

        return $this->webProxyClient->process();
    }
}
