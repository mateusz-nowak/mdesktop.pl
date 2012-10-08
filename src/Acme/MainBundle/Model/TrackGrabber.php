<?php

namespace Acme\MainBundle\Model;

use Acme\MainBundle\ObjectValue\Track;
use Acme\MainBundle\DependencyInjection\FactoryInterface;

class TrackGrabber
{
    /** @var $factory \Acme\MainBundle\DependencyInjection\FactoryInterace */
    protected $factory;

    /** @var array $parameters */
    protected $parameters = array();

    /** @var string $responseBody */
    protected $responseBody;
    
    /** @var bool $isNextPage */
    protected $isNextPage = false;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function isNextPage()
    {
        return $this->isNextPage;
    }

    public function setParameters(array $parameters = array())
    {
        $this->parameters = array_merge($this->parameters, $parameters);
    }

    public function process()
    {
        return $this->factory->searchForTrack($this->getParameter('query'), $this->getParameter('page'), $this->isNextPage);
    }

    public function processDownload(Track $track)
    {
        return $this->factory->processDownload($track);
    }

    protected function getParameter($parameter)
    {
        if (isset($this->parameters[$parameter]))
        {
            return $this->parameters[$parameter];
        }
    }
}
