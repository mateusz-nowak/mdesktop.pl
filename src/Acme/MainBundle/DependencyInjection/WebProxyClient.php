<?php

namespace Acme\MainBundle\DependencyInjection;

use Buzz\Browser;
use Buzz\Message\RequestInterface;

class WebProxyClient
{
    /** @var string $proxyUrl */
    protected $proxyUrl = 'http://proxy.my-addr.com';

    /** @var array $parameters */
    protected $parameters = array();

    /** @var string $responseBody */
    protected $responseBody;

    public function setParameters(array $parameters = array())
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function process()
    {
        $client = new Browser;
		
		// get current proxy url
		preg_match('/<form method=\'get\' action=\'(?P<href>.*?)\'/', (string) $client->get($this->proxyUrl), $regexp);
		$parsedProxyUrl = str_replace(':', '', $this->getParameter('url'));
		
        return $client->get($regexp['href'] . 'myaddrproxy.php/' . $parsedProxyUrl);
    }

    protected function getParameter($parameter)
    {
        if (isset($this->parameters[$parameter])) {
            return $this->parameters[$parameter];
        }
    }
}
