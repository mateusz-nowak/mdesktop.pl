<?php

namespace Acme\MainBundle\DependencyInjection;

use Acme\MainBundle\Entity\Track;

class Downloader
{

    /** @var $prefixTitle string */
    protected $prefixTitle;

    public function setPrefixTitle($prefixTitle)
    {
        $this->prefixTitle = $prefixTitle;
    }

    public function process(Track $track, $trackDownloadUrl)
    {
        header('Content-type: media/x-mp3');
        header('Content-Transfer-Encoding: Binary');
        header(sprintf('Content-disposition: attachment; filename="(%s) %s.mp3"', $this->prefixTitle, $track->getTitle()));

        readfile($trackDownloadUrl);
    }
}
