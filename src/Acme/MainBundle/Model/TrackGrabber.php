<?php

namespace Acme\MainBundle\Model;

use Acme\MainBundle\Entity\Track;
use Acme\MainBundle\DependencyInjection\FactoryInterface;
use Symfony\Component\HttpFoundation\Response; 
use Symfony\Component\HttpFoundation\Request;
use Buzz\Browser;

class TrackGrabber
{
    /** @var $factory \Acme\MainBundle\DependencyInjection\FactoryInterace */
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

    public function process()
    {
        return $this->factory->searchForTrack($this->getParameter('query'), $this->getParameter('page'));
    }
    
    public function processDownload(Track $track)
    {
        $response = new Response();
        $response->setStatusCode(200); 
        
        $response->headers->set('Content-Type', 'media/x-mp3'); 
        $response->headers->set('Content-Disposition', sprintf('attachment;filename="%s.mp3"', $track->getTitle())); 
        $response->headers->set('X-Sendfile', $this->factory->processDownload($track));
        
        return $response;
    }

    protected function getParameter($parameter)
    {
        if (isset($this->parameters[$parameter])) {
            return $this->parameters[$parameter];
        }
    }
}
