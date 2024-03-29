<?php

namespace Acme\MainBundle\Model;

use SimpleXMLIterator;

class Gossip
{
    const URL = '../app/Resources/gossips.xml';

    public static function toArray()
    {
        $itXml = new SimpleXMLIterator(file_get_contents(self::URL));
        $result = array();

        foreach ($itXml->xpath('channel/item') as $node => $value) {
            $result[] = array(
                'image' => (string) $value->enclosure['url'],
                'title' => (string) $value->title,
                'body' => (string) $value->description,
                'url' => (string) $value->link,
            );
        }

        return $result;
    }
}
