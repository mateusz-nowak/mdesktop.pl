<?php

namespace Acme\MainBundle\Model;

class TrackGrabber
{
	/** @var $factory \Acme\MainBundle\Model\FactoryInterace */
	protected $factory;
		
	/** @var array $parameters */
	protected $parameters = array();
	
	/** @var string $responseBody */
	protected $responseBody;
	
	public function __construct(FactoryInterface $factory) 
	{
		$this->factory = $factory;
	}
	
	public function setParameters(array $parameters = array())
	{
		$this->parameters = array_merge($this->parameters, $parameters);
	}
	
	public function getResponse()
	{
		return $this->factory->searchForTrack($this->getParameter('query'), $this->getParameter('page'));
	}
	
	protected function getParameter($parameter)
	{
		if(isset($this->parameters[$parameter]))
		{
			return $this->parameters[$parameter];
		}
	}
}
