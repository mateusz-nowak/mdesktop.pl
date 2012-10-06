<?php

namespace Acme\MainBundle\Model;

use Buzz\Browser;

class WebProxyClient
{
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
		
		return $client->get($this->getParameter('url'));
	}
	
	protected function getParameter($parameter)
	{
		if(isset($this->parameters[$parameter]))
		{
			return $this->parameters[$parameter];
		}
	}
}