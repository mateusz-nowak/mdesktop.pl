<?php

namespace Acme\MainBundle\DependencyInjection\Factory;

$libPear = DIRNAME(DIRNAME(DIRNAME(__FILE__))) . '/Libs/PEAR/';

set_include_path(get_include_path() . PATH_SEPARATOR . $libPear);
include_once 'HTTP/Request2.php';

use Http_Request2;
use ArrayIterator, Exception, stdClass, RuntimeException;
use Doctrine\ORM\EntityManager;
use Acme\MainBundle\Entity\Track;
use Symfony\Component\DomCrawler\Crawler;
use Acme\MainBundle\DependencyInjection\Downloader;
use Acme\MainBundle\DependencyInjection\WebProxyClient;
use Acme\MainBundle\DependencyInjection\FactoryInterface;

class Ulub implements FactoryInterface
{
    /** @var $string $searchTrackUrl */
    protected static $searchTrackUrl = 'http://www.google.com/search?&rls=en&q=%s';
	
	/** @var $string $searchTrackUrl */
    protected static $searchTrackUrlPage = 'http://www.google.com/search?&rls=en&q=%s&start=%s';

    /** @var $string $trackInfoUrl */
    protected static $trackInfoUrl = 'http://ulub.pl/%s/dev';

    /** @var $string $trackDownloadUrl */
    protected static $trackDownloadUrl = 'http://ulub.pl/listen/%s';

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
    	$em = $this->entityManager;
		
		$headers = get_headers(sprintf('http://ulub.pl/%s/dev', $trackRemoteKey));
		$headers = str_replace('Location: ', '', $headers[4]);
		
		$response = (string) $this->getResponse($headers);
		
		preg_match('/kHz\s(?<size>.*? Mb)/', $response, $size);
		
		$track = $em->getRepository('AcmeMainBundle:Track')->findOneByRemote($trackRemoteKey);
		$track->setSize($size['size']);
		
		$em->merge($track);
		
		return $track;
    }

    public function	searchForTrack($query, $page, &$isNextPage)
    {
        $query = urlencode('site:ulub.pl ' . $query . '+"na komputer"');
		
		try {
			if($page == 1) {
				$response = (string) $this->getResponse(sprintf(self::$searchTrackUrl, $query));
			} else {
				$response = (string) $this->getResponse(sprintf(self::$searchTrackUrlPage, $query, ($page-1)*10));
			}
		} catch (RuntimeException $e) {
			throw new RuntimeException('Nie można było wyszukać tego utworu. Wystąpił problem z połaczeniem z zewnętrzną bazą danych - spróbuj poźniej.');
		}
        
        $trackArray = array();
        $crawler = new Crawler;
        $crawler->addHtmlContent($response);

		foreach($crawler->filter('h3.r a') as $item) {
			preg_match('/ulub.pl\/(?<remote>.*?)\//', $item->getAttribute('href'), $tmp);
			
			if(!isset($tmp['remote'])) 
				continue;
			
			$trackArray[] = array(
				'title' => trim(str_replace('- pobierz MP3', '', $item->nodeValue)),
				'remote' => $tmp['remote'],
			);
		}
		
        if (preg_match('/Next/', $response)) {
            $isNextPage = true;
        }
		
        return new ArrayIterator($this->entityManager->getRepository('AcmeMainBundle:Track')->batchInsertTracks($trackArray));
    }

    public function processDownload(Track $track)
    {
    	$request = new Http_Request2;
		
    	$downloadUrl = sprintf(self::$trackDownloadUrl, $track->getRemote());
		$headers = get_headers(sprintf(self::$trackInfoUrl, $track->getRemote()));
		$headers = get_headers(str_replace('Location: ', '', $headers[4]));
		
		$trackCookie = $this->getCookieArray($headers[4]);
		$sessionCookie = $this->getCookieArray($headers[3]);
		
		$request->addCookie($trackCookie[0], $trackCookie[1]);
		$request->addCookie($sessionCookie[0], $sessionCookie[1]);
		
		$request->setUrl($downloadUrl);
		
		$response = $request->send();
		
		$loc = $response->getHeader('location');

        return $this->downloaderContainer->process($track, $loc);
    }
	
	protected function getCookieArray($header)
	{
		preg_match('/Set-Cookie: (?P<key>.*?)=(?P<value>.*?);/', $header, $tmp);
		
		return array($tmp['key'], $tmp['value']);
	}

    protected function getResponse($url)
    {
        $this->webProxyClient->setParameters(array(
            'url' => $url,
        ));

        return $this->webProxyClient->process();
    }
}
