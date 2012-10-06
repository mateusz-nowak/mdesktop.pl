<?php

namespace Acme\MainBundle\Model\Factory;

use Acme\MainBundle\Model\FactoryInterface;
use Acme\MainBundle\Model\WebProxyClient;
use ArrayIterator;

class Ulub implements FactoryInterface
{
    /** @var $string $searchTrackUrl */
    protected static $searchTrackUrl = 'http://ulub.pl/szukaj.html?q=%s&page=%d';

    /** @var $string $trackInfoUrl */
    protected static $trackInfoUrl = 'http://ulub.pl/%s/redirect';

    /** @var $webProxyClient \Acme\MainBundle\Model\WebProxyClient */
    protected $webProxyClient;

    public function __construct(WebProxyClient $client)
    {
        $this->webProxyClient = $client;
    }

    public function getTrackInfo($trackRemoteKey)
    {

    }

    public function	searchForTrack($query, $page)
    {
        $trackArray = array();

        return new ArrayIterator($trackArray);
    }

    protected function getResponse($url)
    {
        $this->webProxyClient->setParameters(array(
            'url' => $url,
        ));

        return $this->webProxyClient->process();
    }
}
