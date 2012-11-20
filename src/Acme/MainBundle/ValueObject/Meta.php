<?php

namespace Acme\MainBundle\ValueObject;

class Meta
{
    private static $instance = null;
    private $title = array();

    public static function getInstance()
    {
        if (!self::$instance) {
            return self::$instance = new self;
        }

        return self::$instance;
    }

    public function addTitle($title)
    {
        $this->title[] = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getLastTitle()
    {
         if (count($this->title)) {
              return $this->title[count($this->title)-1];
         }
    }
}
