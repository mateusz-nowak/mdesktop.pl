<?php

namespace Acme\MainBundle\ObjectValue;

class Track
{
    /** @var $title string */
    protected $title;
    
    /** @var $remote string */
    protected $remote;
    
    public function setTitle() 
    {
        $this->title = $title;
    }
    
    public function setRemoteId($remote)
    {
        $this->remote = $remote;
    }
    
    public function getTitle()
    {
        return $this->title;
    }
    
    public function getRemoteId()
    {
        return $this->remote;
    }
}
