<?php

namespace Acme\MainBundle\Model;

use Acme\MainBundle\DependencyInjection\FactoryInterface;

interface TrackGrabberInterface
{
    /**
     * Constructor handles the FactoryInterface instance
     */
    public function __construct(FactoryInterface $factory);

    /**
     * Set parameters, available: query, page
     */
    public function setParameters(array $parameters = array());

    /**
     * Process the request for track search
     */
    public function process();

    /**
     * Get single parameter
     */
    protected function getParameter($parameter);
}
